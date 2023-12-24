<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Resident;

class DeletedResident extends Model
{
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}