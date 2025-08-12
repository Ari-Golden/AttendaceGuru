<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\LocationAttendance;
use App\Models\tunjTranspost;
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
            ->join('jadwal_gurus', 'absensis.id_jadwal', '=', 'jadwal_gurus.id_jadwal') // Join dengan tabel jadwal_gurus
            ->select('absensis.*', 
            'users.name as nama_guru',
             'users.id as id_user', 
             'users.id_guru as id_guru', 
             'users.program_studi as mapel',
                'jadwal_gurus.jam_masuk', 
                'jadwal_gurus.jam_pulang'
            );

        // Filter berdasarkan status
        if ($request->has('status') && !empty($request->status) && $request->status !== 'semua') {
            $query->where('absensis.status', $request->status);
        }

        // Pencarian berdasarkan nama guru, status, atau lokasi
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('users.name', 'like', $searchTerm)
                    ->orWhere('absensis.status', 'like', $searchTerm)
                    ->orWhere('absensis.lokasi_absen', 'like', $searchTerm);
            });
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('absensis.tgl_absen', [$request->from_date, $request->to_date]);
        }

        // Sorting
        if ($request->has('sort') && $request->has('direction')) {
            $query->orderBy($request->sort, $request->direction);
        }

        // Clone the query for statistics
        $statsQuery = clone $query;

        // Get statistics from filtered data
        $totalData = $statsQuery->count();
        $totalMasuk = (clone $statsQuery)->where('absensis.status', 'masuk')->count();
        $totalPulang = (clone $statsQuery)->where('absensis.status', 'pulang')->count();

        // Get users with 'guru' role
        $guruRoleId = DB::table('roles')->where('name', 'guru')->value('id');
        $guruUsers = DB::table('model_has_roles')->where('role_id', $guruRoleId)->pluck('model_id');

        // Get 'masuk' attendances for today for "belum absen" count
        $todayMasukCount = DB::table('absensis')
            ->whereIn('guru_id', $guruUsers)
            ->where('tgl_absen', Carbon::now()->toDateString())
            ->where('status', 'masuk')
            ->count();

        $totalKaryawan = count($guruUsers);
        $totalBelumAbsen = $totalKaryawan - $todayMasukCount;

        // Paginate hasil query
        $absensi = $query->paginate(10)->appends($request->query());


        return view('dashboard', compact('absensi', 'totalData', 'totalMasuk', 'totalPulang', 'totalBelumAbsen'));
    }

    // app/Http/Controllers/RewardController.php
    public function reward(Request $request)
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
                'percentage' => $percentage,
            ];
        }
    
        // Kirim data ke view
        return view('reward.index', compact('rewardData', 'transportAmount', 'percentage'));
    }
    


    public function rewardUser(Request $request)
    {
        $user = Auth::user(); // Ambil user yang sedang login

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
        ->where('users.id', $user->id) // Filter berdasarkan ID user yang sedang login
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
                'percentage' => $percentage,
            ];
        }
     
        return view('reward.user', compact('rewardData', 'transportAmount', 'percentage'));
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
            'report' => 'required|string',
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
            ->where('tgl_absen', Carbon::now()->toDateString())
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
            'tgl_absen' => Carbon::now()->toDateString(),
            'jam_absen' => $request->jam_absen,
            'lokasi_absen' => $request->description,
            'report' => $request->report, // Bisa diisi sesuai kebutuhan
        ]);

        return redirect()->route('guru.dashboard')->with('success', 'Absensi berhasil disimpan.');
    }


    public function store(Request $request)
    {


        // Ambil titik koordinat dari database
        $tikorSekolah = LocationAttendance::where('id', 1)->first();


        $request->merge([
            'jam_absen' => str_replace('.', ':', $request->jam_absen),
        ]);

        if (!$tikorSekolah) {
            return redirect()->back()->with('error', 'Data lokasi sekolah tidak ditemukan.');
        }

        $hariAbsen = Carbon::now()->format('l'); // Mendapatkan nama hari dalam bahasa Inggris
        $hariMapping = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $hariDalamBahasa = $hariMapping[$hariAbsen] ?? $hariAbsen;
        $JadwalGuru = DB::table('jadwal_gurus')
            ->where('hari', $hariDalamBahasa)
            ->where('user_id', Auth::id())
            ->first();

        if (!$JadwalGuru) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
        }

        $StandarJamMasuk = $JadwalGuru->jam_masuk;
        $StandarJamPulang = $JadwalGuru->jam_pulang;
        $idJadwal = $JadwalGuru->id_jadwal;


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
            ->where('tgl_absen', Carbon::now()->toDateString())
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


        // Hitung keterlambatan
        $keterlambatan = 0;

        if ($request->status == 'masuk') {
            $jamAbsen = Carbon::parse($request->jam_absen);
            $standarJamMasuk = Carbon::parse($StandarJamMasuk);

            if ($jamAbsen->greaterThan($standarJamMasuk)) {
                // Hitung selisih keterlambatan dari jam masuk
                $keterlambatan = $jamAbsen->diffInMinutes($standarJamMasuk);
            } else {
                $keterlambatan = 0; // Tidak terlambat jika tepat waktu atau lebih awal
            }
        } elseif ($request->status == 'pulang') {
            $jamAbsen = Carbon::parse($request->jam_absen);
            $standarJamPulang = Carbon::parse($StandarJamPulang);

            if ($jamAbsen->lessThan($standarJamPulang)) {
                // Hitung selisih kekurangan jam kerja
                $keterlambatan = $standarJamPulang->diffInMinutes($jamAbsen);
            } else {
                $keterlambatan = 0; // Tidak ada keterlambatan jika absen setelah jam pulang
            }
        }

        // dd('keterlambatan :', $keterlambatan, 'jamAbsen :', $jamAbsen, 'standarJamMasuk :', $standarJamMasuk);
        // Simpan data absensi
        try {
            Absensi::create([
                'guru_id' => Auth::id(), // ID guru yang sedang login
                'foto_selfie' => $fotoSelfiePath,  // Hanya simpan path ke database,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'status' => $request->status,
                'tgl_absen' => Carbon::now()->toDateString(),
                'jam_absen' => $request->jam_absen,
                'lokasi_absen' => $request->description,
                'id_jadwal' => $idJadwal,
                'keterlambatan' => $keterlambatan // Simpan keterlambatan
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
