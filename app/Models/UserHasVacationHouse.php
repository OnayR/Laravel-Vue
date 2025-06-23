<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHasVacationHouse extends Model
{
     protected $fillable = [
        'user_id',
        'vacation_house_id',
        'start_date',
        'end_date',
        'number_of_guests',
    ];
}