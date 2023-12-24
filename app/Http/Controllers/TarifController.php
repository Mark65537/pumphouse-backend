<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarif; // Ensure this matches the namespace and class name of your Tarif model

class TarifController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $query = Tarif::query();
        return $query->get();
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'period_id' => 'required|integer',
            'amount_rub' => 'required|numeric'
        ]);

        $tarif = Tarif::create($validatedData);
        if ($tarif ) {
            $tarif ->update($validatedData);
            return response()->json($tarif , 201);
            // return response()->json(['message' => 'Resident created successfully.']);
        } else {
            return response()->json(['message' => 'Tarif not created.'], 404);
        }
    }

    // Display the specified resource.
    public function show(Tarif $tarif)
    {
        return response()->json($tarif, 200); 
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'period_id' => 'required|integer',
            'amount_rub' => 'required|numeric'
        ]);

        $tarif = Tarif::find($id);
        
        if ($tarif) {
            if (!empty($validatedData)) {
                $tarif->update($validatedData);
            }
            return response()->json(['message' => 'Tarif updated successfully.']);
        } else {
            return response()->json(['message' => 'Tarif not found.'], 404);
        }
    }
}