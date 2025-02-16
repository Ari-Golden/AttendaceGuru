<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'guru_id',
        'foto_selfie',
        'jam_absen',
        'tgl_absen',
        'lokasi_absen',
        'status',
    ];
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
