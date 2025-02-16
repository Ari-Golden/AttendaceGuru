<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input tambahan
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'id_guru' => ['required', 'string', 'max:255'], // Validasi ID Guru
            'program_studi' => ['required', 'string', 'max:255'], // Validasi Program Studi
            'alamat' => ['required', 'string'], // Validasi Alamat
            'no_whatsapp' => ['required', 'string', 'max:15'], // Validasi No WhatsApp
        ]);

        // Buat user baru dengan data tambahan
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_guru' => $request->id_guru, // Simpan ID Guru
            'program_studi' => $request->program_studi, // Simpan Program Studi
            'alamat' => $request->alamat, // Simpan Alamat
            'no_whatsapp' => $request->no_whatsapp, // Simpan No WhatsApp
        ]);

        // Assign role guru (jika menggunakan Spatie Laravel Permission)
        $user->assignRole('guru');

        // Trigger event registered
        event(new Registered($user));

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke halaman dashboard
        return redirect(route('dashboard', absolute: false));
    }
}
