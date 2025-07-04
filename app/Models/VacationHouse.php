<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacationHouse extends Model
{
     protected $fillable = [
        'name',
        'price',
        'description',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];
}