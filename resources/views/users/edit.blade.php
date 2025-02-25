@extends('layouts.guru')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Edit User</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PATCH')
        
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name:</label>
            <input type="text" class="form-input mt-1 block w-full" id="name" name="name" value="{{ $user->name }}" required>
        </div>
        
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email:</label>
            <input type="email" class="form-input mt-1 block w-full" id="email" name="email" value="{{ $user->email }}" required>
        </div>
        
        <div class="mb-4">
            <label for="role" class="block text-gray-700">Role:</label>
            <select class="form-select mt-1 block w-full" id="role" name="role" required>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>
        
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password:</label>
            <input type="password" class="form-input mt-1 block w-full" id="password" name="password">
        </div>
        
        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700">Confirm Password:</label>
            <input type="password" class="form-input mt-1 block w-full" id="password_confirmation" name="password_confirmation">
        </div>

        <div class="mb-4">
            <label for="Id_guru" class="block text-gray-700">Id Guru:</label>
            <input type="text" class="form-input mt-1 block w-full" id="Id_guru" name="phone" value="{{ $user->id_guru }}" required>
        </div>
        <div class="mb-4">
            <label for="mapel" class="block text-gray-700">Mata Pelajaran:</label>
            <input type="text" class="form-input mt-1 block w-full" id="program_studi" name="program_studi" value="{{ $user->program_studi }}" required>
        </div>
        
        <div class="mb-4">
            <label for="phone" class="block text-gray-700">Phone:</label>
            <input type="text" class="form-input mt-1 block w-full" id="phone" name="phone" value="{{ $user->no_whatsapp }}" required>
        </div>

        <div class="mb-4">
            <label for="address" class="block text-gray-700">Address:</label>
            <textarea class="form-textarea mt-1 block w-full" id="address" name="address" required>{{ $user->alamat }}</textarea>
        </div>
        
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</div>
@endsection