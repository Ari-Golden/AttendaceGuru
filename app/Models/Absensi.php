<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'guru_id',
        'id_jadwal',
        'foto_selfie',
        'jam_absen',
        'tgl_absen',
        'lokasi_absen',
        'status',
        'latitude',
        'longitude',
        'report',
        'keterlambatan' // Tambahkan field ini
    ];
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
    public function jadwal()
    {
        return $this->belongsTo(JadwalGuru::class, 'id_jadwal');
    }
}
