<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationAttendance extends Model
{
    protected $fillable = [

        'latitude',
        'longitude',
        'radius',
        'description'
    ];

    
}
