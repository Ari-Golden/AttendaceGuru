<?php

namespace App\Http\Controllers;

use App\Models\ShiftCode;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = ShiftCode::all();

        // Hitung selisih waktu masuk dan pulang untuk setiap shift
        foreach ($shifts as $shift) {
            try {
                $jamMasuk = Carbon::createFromFormat('H:i:s', $shift->jam_masuk);
                $jamPulang = Carbon::createFromFormat('H:i:s', $shift->jam_pulang);
            } catch (\Exception $e) {
                // Jika format waktu tidak sesuai, konversi ke objek Carbon
                $jamMasuk = Carbon::parse($shift->jam_masuk);
                $jamPulang = Carbon::parse($shift->jam_pulang);
            }

            $selisih = $jamMasuk->diff($jamPulang);

            // Tambahkan selisih waktu ke dalam objek shift
            $shift->selisih = $selisih->format('%H:%I:%S');
        }

        return view('shift.shiftCode', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shift.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'note' => 'required',
        ]);

        ShiftCode::create($request->all());
        return redirect()->route('shift-code.index')->with('success', 'Shift Code berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShiftCode $shiftCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShiftCode $shiftCode)
    {

        return view('shift.edit', compact('shiftCode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShiftCode $shiftCode)
    {
        $request->validate([
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'note' => 'required',
        ]);

        $shiftCode->update($request->all());
        return redirect()->route('shift-code.index')->with('success', 'Shift Code berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShiftCode $shiftCode)
    {
        $shiftCode->delete();
        return redirect()->route('shift-code.index')->with('success', 'Shift Code berhasil dihapus');
    }
}
