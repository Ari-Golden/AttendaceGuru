@extends('layouts.guru')

@section('content')
    <div class="max-w-lg p-6 mx-auto mt-10 bg-white rounded-lg shadow-md">
        <h2 class="mb-6 text-2xl font-bold text-center">Edit Shift Schedule</h2>

        @if (session('success'))
            <div class="p-2 mb-4 text-green-700 bg-green-100 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('shift-schedule.update', $shiftSchedule->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- ID Guru -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">ID Guru</label>
                <input type="text" name="id_guru" value="{{ $shiftSchedule->id_guru }}"
                    class="w-full p-2 mt-1 bg-gray-100 border rounded-md" readonly>
            </div>

            <!-- Nama Guru -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama Guru</label>
                <input type="text" name="nama" value="{{ $shiftSchedule->nama_guru ?? 'Tidak Diketahui' }}"
                    class="w-full p-2 mt-1 bg-gray-100 border rounded-md" readonly>
            </div>

            <!-- Pilihan Shift -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Pilih Shift</label>
                <input type="hidden" name="shift_code" value="{{ $shiftSchedule->shift_code }}">

                <input type="text" name="nama_shift" value="{{ $shiftSchedule->shift_note ?? 'Tidak Diketahui' }}"
                    class="w-full p-2 mt-1 bg-gray-100 border rounded-md" readonly>
                <select name="shift_code" id="shift_code"
                    class="w-full p-2 mt-1 border rounded-md focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Shift --</option>
                    @foreach ($shiftCodes as $shift)
                        <option value="{{ $shift->note }}"
                            {{ $shiftSchedule->shift_code == $shift->note ? 'selected' : '' }}>
                            {{ $shift->id }} ({{ $shift->note }})
                        </option>
                    @endforeach
                </select>
                @error('shift_code')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jam Masuk -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Jam Masuk</label>
                <input type="time" name="jam_masuk" value="{{ old('jam_masuk', $shiftSchedule->jam_masuk) }}"
                    class="w-full p-2 mt-1 border rounded-md focus:ring-2 focus:ring-blue-500">
                @error('jam_masuk')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jam Pulang -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Jam Pulang</label>
                <input type="time" name="jam_pulang" value="{{ old('jam_pulang', $shiftSchedule->jam_pulang) }}"
                    class="w-full p-2 mt-1 border rounded-md focus:ring-2 focus:ring-blue-500">
                @error('jam_pulang')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div class="flex justify-between">
                <button type="submit"
                    class="w-full px-4 py-2 text-white transition bg-blue-500 rounded-md hover:bg-blue-600">
                    Update Shift
                </button>
                <a href="{{ url('/shift-schedules') }}"
                    class="w-full py-2 ml-2 text-center text-white transition bg-blue-500 rounded-md mpx-4 hover:bg-blue-600">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
