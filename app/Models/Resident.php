<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'fio',
        'area',
        'start_date',
    ];

    /**
     * Create a new resident instance.
     *
     * @param array $validatedData
     * @return static
     */
    public static function create(array $validatedData)
    {
        return static::query()->create($validatedData);
    }
}