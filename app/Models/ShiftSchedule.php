<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['id_guru', 'shift_code'];

    public function guru()
    {
        return $this->belongsTo(User::class, 'id_guru', 'id');
    }

    public function shift()
    {
        return $this->belongsTo(ShiftCode::class, 'shift_code', 'id');
    }
}
