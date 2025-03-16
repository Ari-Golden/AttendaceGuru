<?php

namespace App\Exports;

use App\Models\tunjTranspost;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ExportTransport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */


    public function view(): View
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

          $amountTransport = TunjTranspost::where('id', 1)->value('amount');      


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
          ];
      }


        return view('reward.reportAbsen', [
            'rewardData' => $rewardData,
            
        ]);
    }
}
