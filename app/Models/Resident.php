<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;
    protected $fillable = [
        'fio',
        'area',
        'start_date',
    ];
}