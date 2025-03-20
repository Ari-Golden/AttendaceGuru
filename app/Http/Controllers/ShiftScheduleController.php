<?php

namespace App\Http\Controllers;

use App\Models\ShiftCode;
use App\Models\ShiftSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $schedules = DB::table('shift_schedules')
            ->join('users', 'shift_schedules.id_guru', '=', 'users.id')
            ->join('shift_codes', 'shift_schedules.shift_code', '=', 'shift_codes.id')
            ->select(
                'shift_schedules.*',
                'users.id',
                'users.id_guru',
                'users.name as nama_guru',
                'shift_codes.note as shift_note',
                'shift_codes.jam_masuk',
                'shift_codes.jam_pulang'
            )
            ->get();
        return view('schedule.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shifts = ShiftCode::all();
        $users = User::whereNotNull('id_guru')->get();

        return view('schedule.create', compact('shifts', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
          public function store(Request $request)
{
    // Validasi request
    $request->validate([
        'shift_code' => 'required|exists:shift_codes,id',
        'selected_users' => 'required|json' // Pastikan JSON yang valid
    ]);

    // Decode JSON selected_users
    $selectedUsers = json_decode($request->selected_users, true);

    // Pastikan hasil decoding adalah array yang tidak kosong
    if (!is_array($selectedUsers) || empty($selectedUsers)) {
        return redirect()->back()->with('error', 'Format data tidak valid atau kosong!');
    }

    // Ambil daftar ID user yang valid dari tabel users
    $validUsers = \DB::table('users')->whereIn('id', $selectedUsers)->pluck('id')->toArray();

    if (empty($validUsers)) {
        return redirect()->back()->with('error', 'Tidak ada user yang valid ditemukan!');
    }

    // Ambil daftar id_user yang sudah memiliki shift_code ini
    $existingUsers = \DB::table('shift_schedules')
        ->where('shift_code', $request->shift_code)
        ->pluck('id_guru') // Menggunakan id_user, bukan id_guru
        ->toArray();

    // Filter hanya user yang belum memiliki shift_code ini
    $newUsers = array_diff($validUsers, $existingUsers);

    if (empty($newUsers)) {
        return redirect()->back()->with('error', 'Semua user yang dipilih sudah memiliki shift ini atau tidak valid!');
    }

    // Siapkan data untuk batch insert
    $data = array_map(function ($user_id) use ($request) {
        return [
            'id_guru' => $user_id, // Simpan sebagai id_user, bukan id_guru
            'shift_code' => $request->shift_code,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }, $newUsers);

    // Simpan data ke tabel shift_schedules dalam satu query
    \DB::table('shift_schedules')->insertOrIgnore($data);

    return redirect()->route('shift-schedules.index')->with('success', 'Shift schedule berhasil disimpan!');
}



    /**
     * Display the specified resource.
     */
    public function show(ShiftSchedule $shiftSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil data shift schedule berdasarkan ID
        $shiftSchedule = DB::table('shift_schedules')
            ->join('users', 'shift_schedules.id_guru', '=', 'users.id')
            ->join('shift_codes', 'shift_schedules.shift_code', '=', 'shift_codes.id')
            ->select(
                'shift_schedules.*',
                'users.name as nama_guru',
                'shift_codes.note as shift_note',
                'shift_codes.jam_masuk',
                'shift_codes.jam_pulang'
            )
            ->where('shift_schedules.id', $id)
            ->first();

        // Cek apakah data ditemukan
        if (!$shiftSchedule) {
            return redirect()->back()->with('error', 'Shift Schedule tidak ditemukan!');
        }

        // Ambil semua shift code untuk dropdown
        $shiftCodes = ShiftCode::select('id', 'note')->get();


        return view('schedule.edit', compact('shiftSchedule', 'shiftCodes'));
    }


    /**
     * Memperbarui shift schedule di database.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'shift_code' => 'required|exists:shift_codes,code', // Pastikan shift_code ada di tabel shift_codes
        ]);

        // Ambil data shift yang akan diperbarui
        $shiftSchedule = ShiftSchedule::findOrFail($id);

        // Update shift_code
        $shiftSchedule->update([
            'shift_code' => $request->shift_code,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('shift-schedule.edit', $id)->with('success', 'Shift schedule berhasil diperbarui.');
    }
    /**
     * Remove the specified resource from storage.
     */
     
  public function destroy($id_guru)
{
    // Periksa apakah data dengan id_guru tersebut ada di database
    $data = DB::table('shift_schedules')->where('id_guru', $id_guru)->get();

    // Jika data tidak ditemukan, kembalikan pesan error
    if ($data->isEmpty()) {
        return redirect()->back()->with('error', 'Data shift schedule tidak ditemukan!');
    }

    // Hapus semua data shift_schedules dengan id_guru tersebut
    DB::table('shift_schedules')->where('id_guru', $id_guru)->delete();

    // Redirect dengan pesan sukses
    return redirect()->back()->with('success', 'Shift schedule berhasil dihapus!');
}

}
