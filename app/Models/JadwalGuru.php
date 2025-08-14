<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalGuru extends Model
{
    use HasFactory;
   protected $table = 'jadwal_gurus';
    protected $primaryKey = 'id_jadwal';
    protected $fillable = [
        'user_id',
        'hari',
        'jam_masuk',
        'jam_pulang',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
