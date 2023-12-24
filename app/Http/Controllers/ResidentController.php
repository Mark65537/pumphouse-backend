<?php

namespace App\Http\Controllers;

use App\Models\Resident;
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
        DeletedResident::create($resident->toArray());
    } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Resident::query();

        if ($request->has('search')) {
            $query->where('fio', 'like', '%' . $request->get('search') . '%');
        }
        // mb_convert_encoding($result, 'ISO-8859-1', 'UTF-8');
        return $query->get()->toJson(JSON_UNESCAPED_UNICODE);;
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
            return response()->json(['message' => 'Resident updated successfully.']);
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
                $this->moveToDeletedResidents($resident);
                return response()->json(['message' => $e], 500);
            }
        } else {
            return response()->json(['message' => 'Resident not found.'], 404);
        }
    }
}