<?php

namespace App\Http\Controllers;

use App\Exports\ExportTransport;
use App\Models\tunjTranspost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Browsershot\Browsershot;

class reportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Query dasar untuk data absensi
        $query = DB::table('absensis')
            ->join('users', 'absensis.guru_id', '=', 'users.id')
            ->join('jadwal_gurus', 'absensis.id_jadwal', '=', 'jadwal_gurus.id_jadwal')
            ->select(
                'users.name as nama_guru',
                'users.id as id_user',
                'users.id_guru as id_guru',
                'users.program_studi as mapel',
                DB::raw('MAX(jadwal_gurus.jam_masuk) as standar_masuk'),
                DB::raw('MAX(jadwal_gurus.jam_pulang) as standar_pulang'),
                DB::raw('MAX(absensis.tgl_absen) as tgl_absen'),
                DB::raw('MAX(CASE WHEN absensis.status = "Masuk" THEN absensis.jam_absen END) as jam_masuk'),
                DB::raw('MAX(absensis.keterlambatan) as keterlambatan'),
                DB::raw('MAX(CASE WHEN absensis.status = "pulang" THEN absensis.jam_absen END) as jam_pulang')
            )
            ->groupBy('users.id_guru', 'users.name', 'users.id', 'users.program_studi');


        // Filter berdasarkan tanggal absen jika ada
        if ($request->has('from_date') && $request->has('until_date')) {
            $fromDate = $request->input('from_date');
            $untilDate = $request->input('until_date');
            $query->whereBetween('absensis.tgl_absen', [$fromDate, $untilDate]);
        }

        // Ambil jumlah uang transport
        $amountTransport = TunjTranspost::where('id', 1)->value('amount');
        $transportAmount = $amountTransport;

        // Ambil data absensi guru
        $rewards = $query->get();

        // Jika tidak ada data, kirim pesan ke view
        if ($rewards->isEmpty()) {
            return view('reward.index', [
                'rewardData' => [],
                'transportAmount' => $transportAmount,
                'percentage' => 100,
                'noDataMessage' => 'Belum ada data absensi untuk periode ini.'
            ]);
        }

        // Variabel untuk menyimpan data reward
        $rewardData = [];

        foreach ($rewards as $reward) {
            $percentage = 100; // Mulai dari 100%, nanti dikurangi sesuai keterlambatan
            $diffMasuk = 0;
            $diffPulang = 0;

            // Konversi waktu ke Carbon
            $jamMasuk = Carbon::parse($reward->standar_masuk);
            $jamPulang = Carbon::parse($reward->standar_pulang);
            $absensiMasuk = $reward->jam_masuk ? Carbon::parse($reward->jam_masuk) : null;
            $absensiPulang = $reward->jam_pulang ? Carbon::parse($reward->jam_pulang) : null;

            // Hitung keterlambatan masuk
            if (!is_null($absensiMasuk) && $absensiMasuk->greaterThan($jamMasuk)) {
                $diffMasuk = $absensiMasuk->diffInMinutes($jamMasuk);
                if ($diffMasuk >= 45) {
                    $percentage -= 75;
                } elseif ($diffMasuk >= 30) {
                    $percentage -= 50;
                } elseif ($diffMasuk >= 15) {
                    $percentage -= 25;
                }
            }

            // Hitung keterlambatan pulang (pulang lebih awal)
            if (!is_null($absensiPulang) && $absensiPulang->lessThan($jamPulang)) {
                $diffPulang = $jamPulang->diffInMinutes($absensiPulang);
                if ($diffPulang >= 45) {
                    $percentage -= 75;
                } elseif ($diffPulang >= 30) {
                    $percentage -= 50;
                } elseif ($diffPulang >= 15) {
                    $percentage -= 25;
                }
            }

            // Pastikan persentase minimal 0%
            $percentage = max($percentage, 0);

            // Hitung reward transport berdasarkan persentase kehadiran
            $transportReward = $transportAmount * ($percentage / 100);

            $rewardData[] = [
                'reward' => $reward,
                'diffMasuk' => $diffMasuk,
                'diffPulang' => $diffPulang,
                'transportReward' => $transportReward,
                'transportAmount' => $transportAmount,
                'percentage' => $percentage,
            ];
        }

        // Kirim data ke view
        return view('reward.reportAbsen', compact('rewardData', 'transportAmount','transportReward', 'percentage'));
    }

    public function exportExcel()
    {
        return Excel::download(new ExportTransport, 'reportTransport.xlsx');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
