<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index(Request $request)
    {
            // Query for bills with relations
        $query = Bill::with([
            'resident', 
            'period.pumpMeterRecord'
        ]);

        // Fetch the bills
        $bills = $query->get();
        // Return bills in JSON format with Unicode support
        return response()->json($bills, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resident  $resident
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        return response()->json($bill, 200, [], JSON_UNESCAPED_UNICODE);
    }
}