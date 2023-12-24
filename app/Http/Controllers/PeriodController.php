<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;
use Carbon\Carbon;

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
        if (preg_match('/^\d{4}-\d{2}$/', $request->date)) {
            $date = Carbon::createFromFormat('Y-m', $request->date);
            $request->merge([
                'begin_date' => $date->startOfMonth()->toDateString(),
                'end_date' => $date->endOfMonth()->toDateString()
            ]);
        }

        $validatedData = $request->validate([
            'begin_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:begin_date'
        ]);

        $period = Period::create($validatedData);

        if ($period) {
            return response()->json($period, 201);
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