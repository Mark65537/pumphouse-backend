<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\DeletedResident;
use App\Models\Period;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    /**
     * Move resident data to deleted_residents table.
     *
     * @param \App\Models\Resident $resident
     */
    private function moveToDeletedResidents($resident)
    {
        $startDate = $resident->start_date;
        $endDate = date('Y-m-d');
    
        $period = Period::firstOrCreate(
            [
                'begin_date' => $startDate,
                'end_date' => $endDate
            ],
            ['created_at' => now()]
        );
        
        $deletedResident = DeletedResident::create([
            'resident_id' => $resident->id,
            'period_id' => $period->id
        ]);

        if ($deletedResident) {
            return response()->json(['message' => 'Resident move to DeletedResidents successfully.']);
        } else {
            return response()->json(['message' => 'Resident not created.'], 404);
        }
    }

    /**
     * Display a listing of non-deleted residents.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $searchTerm = $request->get('search');
            $residents = Resident::query()
            ->when($searchTerm, function ($query, $searchTerm) {
                return $query->where('fio', 'like', "%{$searchTerm}%");
            })
            ->whereNotIn('id', DeletedResident::select('resident_id'))
            ->get();
        } catch (\Exception $e) {
            //Если нет удаленных дачников, то возвращаем все
            if ($e->getCode() == 'HY000') {
                $residents = Resident::all();
            } else {
                throw $e;
            }
        }

        return $residents->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fio' => 'required|string|max:255',
            'area' => 'required|numeric',
            'start_date' => 'required|date',
        ]);

        $resident = Resident::create($validatedData);

        if ($resident) {
            $resident->update($validatedData);
            return response()->json($resident, 201);
            // return response()->json(['message' => 'Resident created successfully.']);
        } else {
            return response()->json(['message' => 'Resident not created.'], 404);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resident  $resident
     * @return \Illuminate\Http\Response
     */
    public function show(Resident $resident)
    {
        return response()->json($resident, 200, [], JSON_UNESCAPED_UNICODE); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'fio' => 'nullable|string|max:255',
            'area' => 'nullable|numeric',
            'start_date' => 'nullable|date',
        ]);
        $resident = Resident::find($id);
        
        if ($resident) {
            if (!empty($validatedData)) {
                $resident->update($validatedData);
            }
            return response()->json(['message' => 'Resident updated successfully.'], 200);
        } else {
            return response()->json(['message' => 'Resident not found.'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resident  $resident
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resident = Resident::find($id);

        if ($resident) {
            try {
                $resident->delete();
                return response()->json(['message' => 'Resident deleted successfully.']);
            } catch (\Exception $e) {
                try {
                    return $this->moveToDeletedResidents($resident);
                } catch (\Exception $e) {
                    return response()->json(['message' => $e], 500);
                }
            }
        } else {
            return response()->json(['message' => 'Resident not found.'], 404);
        }
    }
}