@extends('layouts.attendance')

@section('title', 'Absensi Guru')

@section('content')
    <div class="max-w-md p-6 mx-auto bg-white rounded shadow-md">
        <h2 class="mb-4 text-xl font-bold">Absensi Guru</h2>

        <form action="{{ route('user.absensi.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Input Foto Selfie -->
            <div>
                <label for="foto_selfie" class="block text-sm font-medium text-gray-700">Foto Selfie</label>
                <input type="file" id="foto_selfie" name="foto_selfie" accept="image/*" capture="camera"
                    class="block w-full mt-1 border-gray-300 rounded-md">
                @error('foto_selfie')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Lokasi Absen -->
            <div>
                <label for="lokasi_absen" class="block text-sm font-medium text-gray-700">Lokasi Absen</label>
                <input type="text" id="lokasi_absen" name="lokasi_absen" value="{{ old('lokasi_absen') }}"
                    placeholder="Masukkan lokasi absen" class="block w-full mt-1 border-gray-300 rounded-md">
                @error('lokasi_absen')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Absen Masuk dan Pulang -->
            <div class="flex space-x-4">
                <button type="submit" name="status" value="masuk"
                    class="w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">Absen Masuk</button>
                <button type="submit" name="status" value="pulang"
                    class="w-full px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">Absen Pulang</button>
            </div>
        </form>
    </div>
@endsection
