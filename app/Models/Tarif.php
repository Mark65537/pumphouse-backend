<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}