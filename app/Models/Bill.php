<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Resident;

class Bill extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $fillable = [
        'resident_id',
        'period_id',
        'amount_rub',
    ];
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}