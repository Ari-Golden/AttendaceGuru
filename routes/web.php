<?php

use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AttendanceLocationController;
use App\Http\Controllers\JadwalGuruController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\reportController;
use App\Http\Controllers\ShiftCodeController;
use App\Http\Controllers\ShiftScheduleController;
use App\Http\Controllers\TunjTranspostController;
use App\Http\Controllers\userController;
use App\Models\LocationAttendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Halaman Dashboard (hanya untuk admin)
    Route::get('/dashboard', [AbsensiController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:admin');

    // Routes for Absensi editing
    Route::get('/absensi/{absensi}/edit', [AbsensiController::class, 'edit'])
        ->name('absensi.edit')
        ->middleware('role:admin');
    Route::put('/absensi/{absensi}', [AbsensiController::class, 'update'])
        ->name('absensi.update')
        ->middleware('role:admin');

    // Halaman lokasi Absen


    // Tampilkan daftar lokasi
    Route::get('/attendance-locations', [AttendanceLocationController::class, 'index'])
        ->name('attendance-location.index')
        ->middleware('role:admin');

    // Form tambah lokasi
    Route::get('/attendance-location/create', [AttendanceLocationController::class, 'create'])
        ->name('attendance-location.create')
        ->middleware('role:admin');

    // Simpan lokasi baru
    Route::post('/attendance-location', [AttendanceLocationController::class, 'store'])
        ->name('attendance-location.store')
        ->middleware('role:admin');

    // Tampilkan detail lokasi
    Route::get('/attendance-location/{locationAttendance}', [AttendanceLocationController::class, 'show'])
        ->name('attendance-location.show')
        ->middleware('role:admin');

    // Form edit lokasi
    Route::get('/attendance-location/{locationAttendance}/edit', [AttendanceLocationController::class, 'edit'])
        ->name('attendance-location.edit')
        ->middleware('role:admin');

    // Update lokasi
    Route::put('/attendance-location/{locationAttendance}', [AttendanceLocationController::class, 'update'])
        ->name('attendance-location.update')
        ->middleware('role:admin');

    // Hapus lokasi
    Route::delete('/attendance-location/{locationAttendance}', [AttendanceLocationController::class, 'destroy'])
        ->name('attendance-location.destroy')
        ->middleware('role:admin');



    // halaman data users
    Route::get('/users', [userController::class, 'index'])
        ->name('users.index')
        ->middleware('role:admin');

    Route::get('/users/create', [userController::class, 'create'])
        ->name('users.create')
        ->middleware('role:admin');

    Route::post('/users', [userController::class, 'store'])
        ->name('users.store')
        ->middleware('role:admin');

    Route::post('/users/{id}/assign-role', [UserController::class, 'assignRole'])
    ->name('users.assignRole')
    ->middleware('role:admin');

    Route::get('/users/{id}/edit', [userController::class, 'edit'])
        ->name('users.edit')
        ->middleware('role:admin');
    Route::patch('/users/{id}', [userController::class, 'update'])
        ->name('users.update')
        ->middleware('role:admin');
    Route::delete('/users/{id}', [userController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('role:admin');

        // jadwal guru

    Route::get('/jadwal-guru', [JadwalGuruController::class, 'index'])        
    ->name('jadwal_guru.index');
Route::get('/jadwal-guru/create', [JadwalGuruController::class, 'create'])
    ->name('jadwal-guru.create')
    ->middleware('role:admin');
Route::get('/jadwal-guru/create/{id}', [JadwalGuruController::class, 'createbyid'])
    ->name('jadwal-guru.create.id')
    ->middleware('role:admin');
Route::post('/jadwal-guru', [JadwalGuruController::class, 'store'])
    ->name('jadwal-guru.store')
    ->middleware('role:admin');
Route::get('/jadwal-guru/{jadwalGuru}/edit', [JadwalGuruController::class, 'edit'])
    ->name('jadwal-guru.edit')
    ->middleware('role:admin');
Route::patch('/jadwal_guru/{jadwalGuru}', [JadwalGuruController::class, 'update'])
    ->name('jadwal_guru.update')
    ->middleware('role:admin')
    ->where('jadwalGuru', '[0-9]+'); // Ensure jadwalGuru is a number
Route::delete('/jadwal-guru/{jadwalGuru}', [JadwalGuruController::class, 'destroy'])
    ->name('jadwal-guru.destroy')
    ->middleware('role:admin');


    // Halaman Shift Schedule (hanya untuk admin)
    Route::get('/shift-schedules', [ShiftScheduleController::class, 'index'])
        ->name('shift-schedules.index')
        ->middleware('role:admin');
    Route::post('/shift-schedules', [ShiftScheduleController::class, 'store'])
        ->name('shift-schedules.store')
        ->middleware('role:admin');
    Route::get('/shift-schedules/create', [ShiftScheduleController::class, 'create'])
        ->name('shift-schedules.create')
        ->middleware('role:admin');
    Route::get('/shift-schedules/{shiftSchedule}/edit', [ShiftScheduleController::class, 'edit'])
        ->name('shift-schedules.edit')
        ->middleware('role:admin');
    Route::patch('/shift-schedules/{shiftSchedule}', [ShiftScheduleController::class, 'update'])
        ->name('shift-schedules.update')
        ->middleware('role:admin');
    Route::delete('/shift-schedules/{shiftSchedule}', [ShiftScheduleController::class, 'destroy'])
        ->name('shift-schedules.destroy')
        ->middleware('role:admin');

    // Halaman Shift Code (hanya untuk admin)
    Route::get('/shift-code', [ShiftCodeController::class, 'index'])
        ->name('shift-code.index')
        ->middleware('role:admin|guru');
    Route::post('/shift-code', [ShiftCodeController::class, 'store'])
        ->name('shift-code.store')
        ->middleware('role:admin');
    Route::get('/shift-code/create', [ShiftCodeController::class, 'create'])
        ->name('shift-code.create')
        ->middleware('role:admin');
    Route::get('/shift-code/{shiftCode}/edit', [ShiftCodeController::class, 'edit'])
        ->name('shift-code.edit')
        ->middleware('role:admin');
    Route::patch('/shift-code/{shiftCode}', [ShiftCodeController::class, 'update'])
        ->name('shift-code.update')
        ->middleware('role:admin');
    Route::delete('/shift-code/{shiftCode}', [ShiftCodeController::class, 'destroy'])
        ->name('shift-code.destroy')
        ->middleware('role:admin');
    // Halaman Absensi (hanya untuk admin)
    Route::get('/shift-schedule', [ShiftScheduleController::class, 'index'])
        ->name('shift-schedule.index')
        ->middleware('role:admin|guru');
    Route::get('/shift-schedule/create', [ShiftScheduleController::class, 'create'])
        ->name('shift-schedule.create')
        ->middleware('role:admin');
    Route::post('/shift-schedule/store', [ShiftScheduleController::class, 'store'])
        ->name('shift-schedule.store')
        ->middleware('role:admin');
    Route::get('/shift-schedule/{shiftSchedule}/edit', [ShiftScheduleController::class, 'edit'])
        ->name('shift-schedule.edit')
        ->middleware('role:admin');
    Route::patch('/shift-schedule/{shiftSchedule}', [ShiftScheduleController::class, 'update'])
        ->name('shift-schedule.update')
        ->middleware('role:admin');
    Route::delete('/shift-schedule/{shiftSchedule}', [ShiftScheduleController::class, 'destroy'])
        ->name('shift-schedule.destroy')
        ->middleware('role:admin');

    // Halaman untuk memberikan reword kepada guru
    Route::get('/reward', [AbsensiController::class, 'reward'])
        ->name('reward')
        ->middleware('role:admin');

    
    
    
    Route::get('/reportTunjangan/pdf',[reportController::class,'index'])
        ->name('reportTunjanganPdf')
        ->middleware('role:admin');

    Route::get('/reportTunjangan/excel',[reportController::class,'exportExcel'])
        ->name('reportTunjanganExcel')
        ->middleware('role:admin');



    Route::get('/transport',[TunjTranspostController::class, 'index'])
        ->name('transport.index')
        ->middleware('role:admin');
    Route::get('/transport/create',[TunjTranspostController::class, 'create'])
        ->name('transport.create')
        ->middleware('role:admin');
    Route::post('/transport',[TunjTranspostController::class, 'store'])
        ->name('transport.store')
        ->middleware('role:admin');
    Route::get('/transport/{id}/edit',[TunjTranspostController::class, 'edit'])
        ->name('transport.edit')
        ->middleware('role:admin');
    Route::patch('/transport/{id}',[TunjTranspostController::class, 'update'])
        ->name('transport.update')
        ->middleware('role:admin');
    Route::delete('/transport/{id}',[TunjTranspostController::class, 'destroy'])
        ->name('transport.destroy')
        ->middleware('role:admin');   


    Route::get('/create-reward', function () {
        return view('welcome');
    })->name('create-reward')
        ->middleware('role:admin');
    Route::post('/reward', [AbsensiController::class, 'rewardStore'])
        ->name('reward.store')
        ->middleware('role:admin');
    Route::get('/reward/{id}/edit', [AbsensiController::class, 'rewardEdit'])
        ->name('reward.edit')
        ->middleware('role:admin');
});

// Halaman Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Halaman Dashboard Guru
Route::middleware('auth')->group(function () {

Route::get('/guru/dashboard', [GuruDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:guru'])
    ->name('guru.dashboard');
    Route::get('/attendanceview',[AttendanceLocationController::class, 'tikorSekolah'])
        ->middleware('role:guru|admin')
        ->name('attendanceview');
        
    Route::get('/get-server-time', function () {
        return response()->json([
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'jam' => Carbon::now()->format('H:i:s'),
        ]);
    })->name('get.server.time');

    Route::get('/attendancePkl',[AttendanceLocationController::class, 'tikorPkl'])
        ->middleware('role:guru|admin')
        ->name('attendancePkl');

    Route::post('/attendance', [AbsensiController::class, 'store'])
        ->name('attendance.store')
        ->middleware('role:guru|admin');

    Route::post('/attendancePkl', [AbsensiController::class, 'attendancePkl'])
        ->name('attendancePkl.store')
        ->middleware('role:guru|admin');

    Route::get('/reward-guru',[AbsensiController::class, 'rewardUser'])
        ->name('reward-guru')
        ->middleware('role:admin');
});

require __DIR__ . '/auth.php';
