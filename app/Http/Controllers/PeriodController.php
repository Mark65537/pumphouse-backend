<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period; // Make sure you have a Period model for the periods table

class PeriodController extends Controller
{
    // Display a listing of periods.
    public function index()
    {
        $query = Period::all();
        return $query->get();
    }

    // Store a newly created period in storage.
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'begin_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:begin_date'
        ]);

        $tarif = Period::create($validatedData);
        if ($tarif ) {
            $tarif ->update($validatedData);
            return response()->json($tarif , 201);
            // return response()->json(['message' => 'Resident created successfully.']);
        } else {
            return response()->json(['message' => 'Period not created.'], 404);
        }
    }

    // Display the specified period.
    public function show(Period $period)
    {
        return response()->json($period, 200); 
    }

    // Update the specified period in storage.
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'begin_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:begin_date'
        ]);

        $period = Period::find($id);
        
        if ($period) {
            if (!empty($validatedData)) {
                $period->update($validatedData);
            }
            return response()->json(['message' => 'Period updated successfully.']);
        } else {
            return response()->json(['message' => 'Period not found.'], 404);
        }
    }
}