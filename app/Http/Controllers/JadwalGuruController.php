<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalGuru;
use App\Models\User;
use Carbon\Carbon;


class JadwalGuruController extends Controller
{
    /**
     * Tampilkan daftar jadwal guru.
     */
    public function index()
    {
        $users = User::all(); // Hanya user dengan role 'guru'
        $jadwal = JadwalGuru::orderByRaw("
        FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')
    ")->get();
        $idjadwal = JadwalGuru::select('id_jadwal')->get()->pluck('id_jadwal');

        return view('jadwal_guru.index', compact('jadwal', 'users', 'idjadwal'));
    }

    /**
     * Form tambah jadwal guru.
     */
    public function create()
    {
        $users = User::with('jadwal')->get(); // Hanya user dengan role 'guru'
        return view('jadwal_guru.create', compact('users'));
    }
    public function createbyid($id)
    {
        $selectedUser = User::find($id);
        $selectedUserName = $selectedUser->name;
        $selectedUserId = $selectedUser->id;

        return view('jadwal_guru.createbyid', compact('selectedUserName', 'selectedUserId'));
    }


    /**
     * Simpan jadwal guru ke database.
     */
    public function store(Request $request)
    {
        
        // Validasi input
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'hari'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_masuk',
        ]);

        

        // Cek apakah jadwal untuk user dan hari yang sama sudah ada
        $existingJadwal = JadwalGuru::where('user_id', $request->user_id)
            ->where('hari', $request->hari)
            ->first();
        
            

        if ($existingJadwal) {
            return redirect()->back()->with('error', 'Jadwal untuk hari ini sudah ada.');
        }
            
        // Simpan data jika belum ada jadwal di hari yang sama
        JadwalGuru::create([
            'user_id'   => $request->user_id,
            'hari'      => $request->hari,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
        ]);

        return redirect()->route('jadwal_guru.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Form edit jadwal guru.
     */
    public function edit($id)   
    {
        $jadwalGuru = JadwalGuru::query()
        ->join('users', 'jadwal_gurus.user_id', '=', 'users.id')
        ->select('jadwal_gurus.*', 'users.name')
        ->where('jadwal_gurus.id_jadwal', $id)
        ->firstOrFail(); // Ambil 1 data, bukan collection
        return view('jadwal_guru.edit', compact('jadwalGuru'));
    }

    /**
     * Update data jadwal guru.
     */
    public function update(Request $request, $id)
    {
       
       $request->merge([
        'jam_pulang' => Carbon::parse($request->jam_pulang)->format('H:i'),
    ]);
    
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            // 'hari'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_masuk',
        ]);

        $jadwal = JadwalGuru::findOrFail($id);
        $jadwal->update($request->only([ 'jam_masuk', 'jam_pulang']));


        return redirect()->route('jadwal_guru.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Hapus jadwal guru.
     */
    public function destroy($id)
    {
        $jadwal = JadwalGuru::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal_guru.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
