<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function pumpMeterRecord()
    {
        return $this->hasOne(PumpMeterRecord::class);
    }
}