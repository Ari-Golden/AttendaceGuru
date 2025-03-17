<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('users.show', [
            'user' => User::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('users.edit', [
            'user' => User::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|string|in:admin,user',
            'password' => 'nullable|min:6|confirmed',
            'id_guru' => 'required|string|max:50',
            'program_studi' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'no_whatsapp' => 'required|string|max:15',
        ]);

        $user = User::findOrFail($id);

        $data = $request->only(['name', 'email', 'role', 'id_guru', 'program_studi', 'alamat', 'no_whatsapp']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.edit', $id)->with('success', 'User berhasil diperbarui');
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
