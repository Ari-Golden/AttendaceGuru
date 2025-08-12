@extends('layouts.guru')

@section('content')
<div class="bg-gray-100 p-6 min-h-screen">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Data Absensi</h1>

        <form method="POST" action="{{ route('absensi.update', $absensi->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="tgl_absen" class="block text-sm font-medium text-gray-700">Tanggal Absen</label>
                <input type="date" name="tgl_absen" id="tgl_absen" value="{{ old('tgl_absen', \Carbon\Carbon::parse($absensi->tgl_absen)->format('Y-m-d')) }}"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('tgl_absen')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jam_absen" class="block text-sm font-medium text-gray-700">Jam Absen</label>
                <input type="time" name="jam_absen" id="jam_absen" value="{{ old('jam_absen', $absensi->jam_absen) }}"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('jam_absen')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Data Absensi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
