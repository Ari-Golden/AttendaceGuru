@extends('layouts.guru')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Edit User</h2>

    @if(session('success'))
        <div class="p-4 mb-4 text-green-800 bg-green-200 border-l-4 border-green-600">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" value="{{ $user->name }}" required
                    class="block w-full p-2 border border-gray-300 rounded-md">
            </div>
            
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" required
                    class="block w-full p-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Role</label>
                <select name="role" required class="block w-full p-2 border border-gray-300 rounded-md">
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Password (Opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                    class="block w-full p-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" placeholder="Masukkan ulang password"
                    class="block w-full p-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">ID Guru</label>
                <input type="text" name="id_guru" value="{{ $user->id_guru }}" required
                    class="block w-full p-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Program Studi</label>
                <input type="text" name="program_studi" value="{{ $user->program_studi }}" required
                    class="block w-full p-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="alamat" required class="block w-full p-2 border border-gray-300 rounded-md">{{ $user->alamat }}</textarea>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">No WhatsApp</label>
                <input type="text" name="no_whatsapp" value="{{ $user->no_whatsapp }}" required
                    class="block w-full p-2 border border-gray-300 rounded-md">
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection