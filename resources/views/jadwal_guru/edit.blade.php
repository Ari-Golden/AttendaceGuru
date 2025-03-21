@extends('layouts.guru')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Edit Jadwal Guru</h2>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>Terjadi kesalahan!</strong>
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('jadwal_guru.update', $jadwalGuru->id_jadwal) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PATCH')

        <!-- Pilih Guru -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Nama Guru</label>
            <input type="text" name="user_name" class="w-full border rounded px-3 py-2" value="{{ $jadwalGuru->user->name }}" readonly disabled>
            <input type="hidden" name="user_id" value="{{ $jadwalGuru->user->id }}">
        </div>

        <!-- Pilih Hari -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Hari</label>
            <input type="hidden" name="id_jadwal" value="{{ $jadwalGuru->id_jadwal }}">
            <input type="text" name="hari" class="w-full border rounded px-3 py-2" value="{{ $jadwalGuru->hari }}" readonly disabled>
        </div>

        <!-- Jam Masuk -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Jam Masuk</label>
            <input type="time" name="jam_masuk" class="w-full border rounded px-3 py-2" value="{{ $jadwalGuru->jam_masuk }}">
        </div>

        <!-- Jam Pulang -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Jam Pulang</label>
            <input type="time" name="jam_pulang" class="w-full border rounded px-3 py-2" value="{{ $jadwalGuru->jam_pulang }}">
        </div>

        <!-- Tombol Simpan -->
        <div class="flex justify-between">
            <a href="{{ route('jadwal_guru.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
        </div>
    </form>
</div>
@endsection
