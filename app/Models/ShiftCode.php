<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftCode extends Model
{
    
    protected $fillable = ['jam_masuk', 'jam_pulang', 'note'];
}
