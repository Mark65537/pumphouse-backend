<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $fillable = [
        'period_id',
        'amount_rub',
    ];
    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}