<?php

namespace App\Http\Controllers;

use App\Exports\ExportTransport;
use App\Models\tunjTranspost;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
    
                $amountTransport = tunjTranspost::where('id', 1)->value('amount');      
    
    
            // Uang transport
            $transportAmount = $amountTransport; // Uang transport tetap
    
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
                    'transportAmount' => $transportAmount
                ];
            }
    
            // Kirim data ke view
            $reviewPdf = view('reward.reportAbsen', compact('rewardData', 'schedules', 'transportAmount', 'percentage', 'transportReward'))->render();

            Browsershot::html($reviewPdf)
                ->showBackground()
                ->margins(10, 10, 10, 10)
                ->format('A4')
                ->save(storage_path('\app\public\reportAbsen.pdf'));
                
    }

    public function exportExcel()
    {
        return Excel::download(new ExportTransport,'reportTransport.xlsx');
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
