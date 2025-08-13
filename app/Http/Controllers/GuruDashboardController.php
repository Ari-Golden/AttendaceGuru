<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use Carbon\Carbon;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();

        $attendance = Absensi::where('guru_id', $user->id)
            ->where('tgl_absen', $today)
            ->orderBy('jam_absen', 'asc')
            ->get();

        return view('guru.dashboardguru', compact('attendance'));
    }
}