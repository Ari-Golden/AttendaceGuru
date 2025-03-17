<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // menampilkan data user
        $users = User::all();
        $roles = Role::all();
        return view('users.index', compact('users','roles'));
    }

     // Assign role ke user tertentu
     public function assignRole(Request $request, $id)
     {
         $user = User::findOrFail($id);
         $role = Role::where('name', $request->role)->first();
 
         if (!$role) {
             return back()->with('error', 'Role tidak ditemukan.');
         }
 
         $user->syncRoles([$role->name]); // Hapus role lama, tambahkan role baru
 
         return back()->with('success', 'Role berhasil diperbarui.');
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
