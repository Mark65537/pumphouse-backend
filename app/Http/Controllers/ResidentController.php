<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        return response()->json($resident, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resident  $resident
     * @return \Illuminate\Http\Response
     */
    public function show(Resident $resident)
    {
        return response()->json($resident);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resident  $resident
     * @return \Illuminate\Http\Response
     */
    public function edit(Resident $resident)
    {
        return response()->json($resident);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resident  $resident
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'fio' => 'required|string|max:255',
            'area' => 'required|numeric',
            'start_date' => 'required|date',
        ]);
        $resident = Resident::find($id);
        
        if ($resident) {
            $resident->update($validatedData);
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
            $resident->delete();
            return response()->json(['message' => 'Resident deleted successfully.']);
        } else {
            return response()->json(['message' => 'Resident not found.'], 404);
        }
    }
}