<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $fillable = [
        'begin_date',
        'end_date',
    ];
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function pumpMeterRecord()
    {
        return $this->hasOne(PumpMeterRecord::class);
    }
}