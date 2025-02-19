<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftCodeController;
use App\Http\Controllers\ShiftScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Halaman Dashboard Guru (hanya untuk admin)
    Route::get('/dashboard', [AbsensiController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:admin');
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
        ->middleware('role:admin');
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
        ->middleware('role:admin');
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
    Route::get('/guru', function () {
        return view('guru.dashboardguru');
    })->middleware('role:guru|admin');
    Route::post('/guru/attendance', [AbsensiController::class, 'store'])->name('guru.attendance.store');
});

require __DIR__ . '/auth.php';
