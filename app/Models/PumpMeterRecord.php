<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PumpMeterRecord extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $fillable = [
        'period_id',
        'amount_volume',
    ];
    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}