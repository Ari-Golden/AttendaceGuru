<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\LocationAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function settingLocation(Request $request) {}


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
    
    
    public function attendancePkl(Request $request)
    {

        
        // Ambil lokasi PKL berdasarkan ID yang dipilih
        $tikorPkl = LocationAttendance::find($request->tikor_pkl);

       
    
        if (!$tikorPkl) {
            return redirect()->back()->with('error', 'Titik Koordinat PKL tidak ditemukan.');
        }
    
        // Ambil data lokasi dari Tikor PKL
        $latitudeTikor = round((float) $tikorPkl->latitude, 7);
        $longitudeTikor = round((float) $tikorPkl->longitude, 7);
        $radiusTikor = (float) $tikorPkl->radius;
        
        $request->merge([
            'jam_absen' => str_replace('.', ':', $request->jam_absen), // Ubah format waktu
        ]);
    
        // Validasi Input
        $request->validate([
            'foto_selfie' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^data:image\/(png|jpeg);base64,/', $value)) {
                    $fail('Format foto selfie tidak valid.');
                }
            }],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'status' => 'required|in:masuk,pulang',
            'tgl_absen' => 'required|date',
            'jam_absen' => 'required|date_format:H:i:s',
            'description' => 'required|string',
            'report'=> 'required|string',
        ]);
        
    
        // Ambil lokasi user dari request dan dibulatkan agar akurat
        $latitudeUser = round((float) $request->latitude, 7);
        $longitudeUser = round((float) $request->longitude, 7);
    
        // Hitung jarak antara lokasi user dengan Tikor PKL menggunakan Haversine Formula
        $earthRadius = 6371000; // Meter
        $dLat = deg2rad($latitudeUser - $latitudeTikor);
        $dLon = deg2rad($longitudeUser - $longitudeTikor);
    
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($latitudeTikor)) * cos(deg2rad($latitudeUser)) *
            sin($dLon / 2) * sin($dLon / 2);
    
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;
    
        // Cek apakah user berada dalam radius yang ditentukan
        if ($distance > $radiusTikor) {
            return redirect()->back()->with('error', 'Absensi gagal! Anda berada di luar radius lokasi absensi.');
        }
    
        // Cek apakah sudah absen hari ini
        $guruId = Auth::id();
    
        $existingAbsensi = Absensi::where('guru_id', $guruId)
            ->where('tgl_absen', $request->tgl_absen)
            ->where('status', $request->status)
            ->exists(); // Menggunakan `exists()` lebih efisien
    
        if ($existingAbsensi) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absensi ' . $request->status . ' hari ini.');
        }
    
        // Proses penyimpanan foto selfie (Base64 ke File)
        if (!$request->filled('foto_selfie')) {
            return back()->with('error', 'Foto selfie wajib diambil!');
        }
    
        $image = $request->input('foto_selfie');
    
        // Pastikan gambar dalam format Base64 yang valid
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $matches)) {
            $image = substr($image, strpos($image, ',') + 1);
            $image = str_replace(' ', '+', $image);
            $imageFormat = strtolower($matches[1]); // Ekstensi gambar (png, jpg, dll.)
    
            // Validasi format gambar yang diizinkan
            if (!in_array($imageFormat, ['jpg', 'jpeg', 'png'])) {
                return back()->with('error', 'Format foto tidak didukung! Gunakan JPG atau PNG.');
            }
    
            // Simpan gambar dengan nama unik
            $imageName = 'selfie_' . time() . '.' . $imageFormat;
            FacadesStorage::disk('public')->put('selfies/' . $imageName, base64_decode($image));
    
            $fotoSelfiePath = 'selfies/' . $imageName; // Path yang disimpan di database
        } else {
            return back()->with('error', 'Foto selfie tidak valid!');
        }
    
        // Simpan data absensi ke database
        Absensi::create([
            'guru_id' => $guruId, // ID guru yang sedang login
            'foto_selfie' => $fotoSelfiePath, // Hanya simpan path ke database
            'latitude' => $latitudeUser,
            'longitude' => $longitudeUser,
            'status' => $request->status,
            'tgl_absen' => $request->tgl_absen,
            'jam_absen' => $request->jam_absen,
            'lokasi_absen' => $request->description,
            'report' => $request->report, // Bisa diisi sesuai kebutuhan
        ]);
    
        return redirect()->route('guru.dashboard')->with('success', 'Absensi berhasil disimpan.');
    }
    





    public function store(Request $request)
    {

        // Ambil titik koordinat dari database
        $tikorSekolah = LocationAttendance::where('id', 2)->first();

        $request->merge([
            'jam_absen' => str_replace('.', ':', $request->jam_absen),
        ]);

        if (!$tikorSekolah) {
            return redirect()->back()->with('error', 'Data lokasi sekolah tidak ditemukan.');
        }

        $latitudeTikor = $tikorSekolah->latitude;
        $longitudeTikor = $tikorSekolah->longitude;
        $radiusTikor = $tikorSekolah->radius;
        $lokasiabsen = $tikorSekolah->description;

        // Validasi input
        $request->validate([
            'foto_selfie' => 'required|string', // Pastikan foto selfie tidak kosong
            'latitude' => 'required|regex:/^-?\d{1,3}\.\d{1,7}$/',
            'longitude' => 'required|regex:/^-?\d{1,3}\.\d{1,7}$/',
            'status' => 'required|in:masuk,pulang', // Status harus "masuk" atau "pulang"
            'tgl_absen' => 'required|date',    // Tanggal absen harus valid (YYYY-MM-DD)
            'jam_absen' => 'required|date_format:H:i:s', // Jam absen harus valid (HH:MM:SS)
            'description' => 'required|string',

        ]);

        // Hitung jarak dengan Haversine formula
        $latitudeUser = $request->latitude;
        $longitudeUser = $request->longitude;
        $earthRadius = 6371000;
        $dLat = deg2rad($latitudeUser - $latitudeTikor);
        $dLon = deg2rad($longitudeUser - $longitudeTikor);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($latitudeTikor)) * cos(deg2rad($latitudeUser)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        if ($distance > $radiusTikor) {
            return redirect()->back()->with('error', 'Absensi gagal! Anda berada di luar radius lokasi absensi.');
        }

        // Cek apakah sudah absen hari ini
        $existingAbsensi = Absensi::where('guru_id', Auth::id())
            ->where('tgl_absen', $request->tgl_absen)
            ->where('status', $request->status)
            ->first();

        if ($existingAbsensi) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absensi ' . $request->status . ' hari ini.');
        }

        // Proses penyimpanan foto
        if ($request->has('foto_selfie')) {
            $image = $request->input('foto_selfie');
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'selfie_' . time() . '.png';

            FacadesStorage::disk('public')->put('selfies/' . $imageName, base64_decode($image));

            $fotoSelfiePath = 'selfies/' . $imageName; // Simpan path di database
        } else {
            return back()->with('error', 'Foto selfie wajib diambil!');
        }

        $latitude = round($request->latitude, 7);
        $longitude = round($request->longitude, 7);
        


        // Simpan data absensi
        try {
            Absensi::create([
                'guru_id' => Auth::id(), // ID guru yang sedang login
                'foto_selfie' => $fotoSelfiePath,  // Hanya simpan path ke database,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'status' => $request->status,
                'tgl_absen' => $request->tgl_absen,
                'jam_absen' => $request->jam_absen,
                'lokasi_absen' => $request->description,

            ]);

            return redirect()->route('guru.dashboard')->with('success', 'Absensi berhasil disimpan.');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }



    // Fungsi untuk menghitung jarak menggunakan rumus Haversine
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) + cos($lat1) * cos($lat2) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Jarak dalam meter
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
