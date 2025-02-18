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
    public function edit(ShiftSchedule $shiftSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShiftSchedule $shiftSchedule)
    {
        $request->validate([
            'id_guru' => 'required|exists:users,id',
            'shift_code' => 'required|exists:shift_codes,id',
        ]);

        $shiftSchedule->update($request->all());

        return response()->json($shiftSchedule);
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
