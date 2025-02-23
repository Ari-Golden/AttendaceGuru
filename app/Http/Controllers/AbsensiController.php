<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Query dasar menggunakan Query Builder
        $query = DB::table('absensis') // Tabel absensi
            ->join('users', 'absensis.guru_id', '=', 'users.id') // Join dengan tabel users
            ->select('absensis.*', 'users.name as nama_guru', 'users.id as id_user', 'users.id_guru as id_guru', 'users.program_studi as mapel'); // Pilih kolom yang diperlukan

        // schedule absensi
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

        // Filter berdasarkan status
        if ($request->has('status') && !empty($request->status) && $request->status !== 'semua') {
            $query->where('absensis.status', $request->status);
        }

        // Pencarian berdasarkan nama guru
        if ($request->has('search') && !empty($request->search)) {
            $query->where('users.name', 'like', '%' . $request->search . '%');
        }

        // Paginate hasil query
        $absensi = $query->paginate(10);


        return view('dashboard', compact('absensi', 'schedules'));
    }

    // app/Http/Controllers/RewardController.php
    public function reward(Request $request)
    {
        // Query dasar menggunakan Query Builder untuk data absensi
        $query = DB::table('absensis')
            ->join('users', 'absensis.guru_id', '=', 'users.id')
            ->select(
                'users.name as nama_guru',
                'users.id as id_user',
                'users.id_guru as id_guru',
                'users.program_studi as mapel',
                DB::raw('MAX(absensis.tgl_absen) as tgl_absen'),
                DB::raw('MAX(CASE WHEN absensis.status = "Masuk" THEN absensis.jam_absen END) as jam_masuk'),
                DB::raw('MAX(CASE WHEN absensis.status = "pulang" THEN absensis.jam_absen END) as jam_pulang')
            )
            ->groupBy('users.id_guru', 'users.name', 'users.id', 'users.program_studi');

        // Filter berdasarkan tanggal absen jika ada
        if ($request->has('from_date') && $request->has('until_date')) {
            $fromDate = $request->input('from_date');
            $untilDate = $request->input('until_date');
            $query->whereBetween('absensis.tgl_absen', [$fromDate, $untilDate]);
        }

        // schedule absensi
        $schedules = DB::table('shift_schedules')
            ->leftJoin('users', 'shift_schedules.id_guru', '=', 'users.id_guru')
            ->leftJoin('shift_codes', 'shift_schedules.shift_code', '=', 'shift_codes.id')
            ->select(
                'shift_schedules.*',
                'users.name as nama_guru',
                'shift_codes.note as shift_note',
                'shift_codes.jam_masuk',
                'shift_codes.jam_pulang'
            )
            ->first();

        // Uang transport
        $transportAmount = 64000; // Uang transport tetap

        // Ambil data absensi guru
        $rewards = $query->get();

        // Variabel untuk menyimpan perhitungan perbedaan waktu
        $rewardData = [];

        foreach ($rewards as $reward) {
            $diffMasuk = null;
            $diffPulang = null;
            $transportReward = 0;

            if ($schedules) {
                $jamMasuk = Carbon::parse($schedules->jam_masuk);
                $jamPulang = Carbon::parse($schedules->jam_pulang);

                $absensiMasuk = Carbon::parse($reward->jam_masuk);
                $absensiPulang = Carbon::parse($reward->jam_pulang);

                // Menghitung selisih waktu antara absensi dan jadwal
                $diffMasuk = $jamMasuk->diffInMinutes($absensiMasuk, false); // false untuk memperbolehkan hasil negatif
                $diffPulang = $jamPulang->diffInMinutes($absensiPulang, false);

                // Tentukan persentase berdasarkan perbedaan waktu
                $percentage = 100; // default reward

                // Jika terlambat 45 menit atau lebih atau pulang lebih cepat 45 menit atau lebih (75% dikurangi)
                if (abs($diffMasuk) >= 45 || abs($diffPulang) >= 45) {
                    $percentage = 25;
                }
                // Jika terlambat 30-44 menit atau pulang lebih cepat 30-44 menit (50% dikurangi)
                elseif (abs($diffMasuk) >= 30 || abs($diffPulang) >= 30) {
                    $percentage = 50;
                }
                // Jika terlambat 15-29 menit atau pulang lebih cepat 15-29 menit (25% dikurangi)
                elseif (abs($diffMasuk) >= 15 || abs($diffPulang) >= 15) {
                    $percentage = 75;
                }

                // Menghitung transport reward berdasarkan jam yang dihitung
                $transportReward = $transportAmount * ($percentage / 100); // Persentase dari uang transport
            }

            $rewardData[] = [
                'reward' => $reward,
                'diffMasuk' => $diffMasuk,
                'diffPulang' => $diffPulang,
                'transportReward' => $transportReward,
                'shift_note' => $schedules->shift_note,
                'percentage' => $percentage,
            ];
        }

        // Kirim data ke view
        return view('reward.index', compact('rewardData', 'schedules', 'transportAmount', 'percentage'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Ubah format jam absen
        $request->merge([
            'jam_absen' => str_replace('.', ':', $request->jam_absen),
        ]);

        // Debug data yang diterima
        // dd($request->all());

        // Validasi input dasar
        $request->validate([
            'foto_selfie' => 'required|string', // Pastikan foto selfie tidak kosong
            'latitude' => 'required|numeric',  // Latitude harus numerik
            'longitude' => 'required|numeric', // Longitude harus numerik
            'status' => 'required|in:masuk,pulang', // Status harus "masuk" atau "pulang"
            'tgl_absen' => 'required|date',    // Tanggal absen harus valid (YYYY-MM-DD)
            'jam_absen' => 'required|date_format:H:i:s', // Jam absen harus valid (HH:MM:SS)
        ]);

        // Cek apakah sudah ada absensi masuk atau pulang pada tanggal yang sama
        $existingAbsensi = Absensi::where('guru_id', Auth::id())
            ->where('tgl_absen', $request->tgl_absen)
            ->where('status', $request->status)
            ->first();

        if ($existingAbsensi) {
            return redirect()->back()->withErrors(['error' => 'Anda sudah melakukan absensi ' . $request->status . ' pada tanggal ini.']);
        }

        try {
            // Simpan data absensi ke database
            Absensi::create([
                'guru_id' => Auth::id(), // ID guru yang sedang login
                'foto_selfie' => $request->foto_selfie,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => $request->status,
                'tgl_absen' => $request->tgl_absen,
                'jam_absen' => $request->jam_absen,
                'lokasi_absen' => $request->lokasi_absen,
            ]);

            return redirect()->back()->with('success', 'Absensi berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        //
    }
}
