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
            ->join('users', 'shift_schedules.id_guru', '=', 'users.id_guru')
            ->join('shift_codes', 'shift_schedules.shift_code', '=', 'shift_codes.id')
            ->select(
                'shift_schedules.*',
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
        // Decode JSON dari selected_users agar menjadi array
        $selectedUsers = json_decode($request->selected_users, true);

        // Pastikan hasil decoding adalah array
        if (!is_array($selectedUsers)) {
            return redirect()->back()->with('error', 'Format data tidak valid!');
        }

        // Validasi input
        $request->validate([
            'shift_code' => 'required|exists:shift_codes,id',
            'selected_users' => 'required'
        ]);

        // Simpan data ke tabel shift_schedules
        foreach ($selectedUsers as $user_id) {
            \DB::table('shift_schedules')->insert([
                'id_guru' => $user_id,
                'shift_code' => $request->shift_code,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Shift schedule berhasil disimpan!');
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
            ->join('users', 'shift_schedules.id_guru', '=', 'users.id_guru')
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
    public function destroy(ShiftSchedule $shiftSchedule)
    {
        $shiftSchedule->delete();
        return response()->json(null, 204);
    }
}
