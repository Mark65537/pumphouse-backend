<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use App\Models\Tarif;
use App\Models\PumpMeterRecord;
use App\Models\DeletedResident;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function store(Request $request)
    {
        
        // Получаем начало и конец предыдущего месяца
        $startDate = (new Carbon('first day of last month'))->format('Y-m-d');
        $endDate = (new Carbon('last day of last month'))->format('Y-m-d');

        // получить период прошлого месяца или создать если не существует 
        $period = $this->findOrCreatePeriod($startDate, $endDate);

        $amountVolume = $request->amount_volume;
        
        //проверяем если запись не существует, то добавляем общее потребления воды в PumpMeterRecord
        if(!PumpMeterRecord::where('period_id', $period->id)->exists()){
            PumpMeterRecord::create([
                'period_id' => $period->id,
                'amount_volume' => $amountVolume
            ]);
        }
        

        // if($period) {
        //     return response()->json($period, 200);
        // }
        try{
            // получаем удаленных дачников
            $excludedIds = DeletedResident::pluck('resident_id');
            // получаем всех дачников без учета удаленных
            $residentIds = Resident::whereNotIn('id', $excludedIds)
                               ->pluck('id');                                                  
        } catch (\Exception $e) {
            //Если нет удаленных дачников, то возвращаем все
            if ($e->getCode() == 'HY000') {
                $residentIds = Resident::pluck('id');  
            } else {
                // Возвращаем неудачное сообщение
                return response()->json(
                    ['message' => $e], 
                    422, 
                    [], 
                    JSON_UNESCAPED_UNICODE
                );
            }
        }

        $totalArea = Resident::sum('area');
        $tarif = Tarif::where('period_id', $period->id)
                            ->firstOrFail()
                            ->amount_rub;
        
        

        // if($tarif){
        //     return response()->json($tarif, 200);
        // }
        // else{
        //     return response()->json(['message' => 'Тариф не найден'], 404);
        // }
        foreach ($residentIds as $residentId) {
            if (Bill::where('resident_id', $residentId)
                  ->where('period_id', $period->id)
                  ->exists()) {
                continue;
            }
            
            $resident = Resident::find($residentId);
            $amountRub = $this->calculateAmountRub(
                $amountVolume, $tarif, $totalArea, $resident->area
            );
            
            $this->createBill($residentId, $period->id, $amountRub);
        }

        
        
        // Возвращаем успешное сообщение
        return response()->json(
            ['message' => 'Запись успешно добавлена'], 
            200, 
            [], 
            JSON_UNESCAPED_UNICODE
        );
    }
    private function findOrCreatePeriod($startDate, $endDate)
    {
        return Period::firstOrCreate(
            ['begin_date' => $startDate, 'end_date' => $endDate]
        );
    }
    private function calculateAmountRub($amountVolume, $tariff, 
                                    $totalArea, $residentArea)
    {
        return ($amountVolume * $tariff / $totalArea) * $residentArea;
    }

    private function createBill($residentId, $periodId, $amountRub)
    {
        Bill::create([
            'resident_id' => $residentId,
            'period_id'   => $periodId,
            'amount_rub'  => $amountRub
        ]);
    }
}