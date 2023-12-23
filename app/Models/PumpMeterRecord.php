<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PumpMeterRecord extends Model
{
    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}