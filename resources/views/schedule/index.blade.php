@extends('layouts.guru')

@section('content')
<div class="max-w-6xl p-6 mx-auto bg-white rounded-lg shadow-md">
    <h2 class="mb-6 text-2xl font-bold text-gray-800">Jadwal Shift Guru</h2>

    @if (session('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Nama Guru</th>
                    <th class="px-4 py-2 border">Shift Code</th>
                    <th class="px-4 py-2 border">Note</th>
                    <th class="px-4 py-2 border">Jam Masuk</th>
                    <th class="px-4 py-2 border">Jam Pulang</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules as $schedule)
                <tr class="border-t">
                    <td class="px-4 py-2 border">{{ $schedule->nama_guru }}</td>
                    <td class="px-4 py-2 text-center border">{{ $schedule->shift_code }}</td>
                    <td class="px-4 py-2 border">{{ $schedule->shift_note }}</td>
                    <td class="px-4 py-2 text-center border">{{ $schedule->jam_masuk }}</td>
                    <td class="px-4 py-2 text-center border">{{ $schedule->jam_pulang }}</td>
                    <td class="px-4 py-2 text-center border">
                        <a href="{{ route('shift-schedule.edit', $schedule->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                        |
                        <form action="{{ route('shift-schedule.destroy', $schedule->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
