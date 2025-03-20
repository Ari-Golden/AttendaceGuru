<?php

namespace App\Http\Controllers;

use App\Models\LocationAttendance;
use Illuminate\Http\Request;

class AttendanceLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

       $locations = LocationAttendance::all();
       $tikorSekolah = LocationAttendance::where('id', 1)
       ->select('radius')
       ->first();
        return view('location.index',compact('locations','tikorSekolah'));
    }

    public function tikorSekolah()
    {

     
       $tikorSekolah = LocationAttendance::all()->where('id', 1)->first();
       
        return view('guru.absensi',compact('tikorSekolah'));
    }

    public function tikorPkl()
    {

     
       $tikorPkl = LocationAttendance::all();
       
        return view('guru.absensiPkl',compact('tikorPkl'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('location.attendanceLocation');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['required', 'integer', 'min:10', 'max:1000'],
            'description' => ['required', 'string', 'max:255']
        ]);
    
        // Cek apakah data sudah ada sebelumnya
        $existing = LocationAttendance::where('latitude', $validated['latitude'])
                                      ->where('longitude', $validated['longitude'])
                                      ->where('radius', $validated['radius'])
                                      ->where('description', $validated['description'])
                                      ->exists();
    
        if (!$existing) {
            // Simpan data hanya jika belum ada
            LocationAttendance::create($validated);
        }
    
        return redirect()->route('attendance-location.index')->with('success', 'Lokasi absensi berhasil disimpan!');
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(LocationAttendance $locationAttendance)
    {
        return view('location.show', compact('locationAttendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $locationAttendance = LocationAttendance::findOrFail($id);
        return view('location.edit', ['location' => $locationAttendance]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LocationAttendance $locationAttendance)
    {
        // Validasi input
        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['required', 'integer', 'min:10', 'max:1000'],
            'description' => ['required', 'string', 'max:255']
        ]);

        // Update data
        $locationAttendance->update($validated);

        // Redirect dengan pesan sukses
        return redirect()->route('attendance-location.index')
                         ->with('success', 'Lokasi absensi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LocationAttendance $locationAttendance)
    {
        $locationAttendance->delete();

        return redirect()->route('attendance-location.index')
                         ->with('success', 'Lokasi absensi berhasil dihapus!');
    }
}
