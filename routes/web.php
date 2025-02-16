<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Halaman Dashboard Guru (hanya untuk admin)
    Route::get('/dashboard', [AbsensiController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:admin');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/guru', function () {
        return view('guru.dashboardguru');
    })->middleware('role:guru|admin');
    Route::post('/guru/attendance', [AbsensiController::class, 'store'])->name('guru.attendance.store');
});

require __DIR__ . '/auth.php';
