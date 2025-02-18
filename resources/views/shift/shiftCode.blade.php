@extends('layouts.guru')

@section('content')
    <div class="max-w-4xl p-6 mx-auto bg-white rounded-lg shadow-md">
        <h2 class="mb-6 text-2xl font-bold text-gray-800">Shift Codes</h2>

        <!-- Tombol Tambah Shift Code -->
        <div class="mb-4">
            <a href="{{ route('shift-code.create') }}"
               class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-700">
                + Tambah Shift Code
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">Shift Code</th>
                        <th class="px-4 py-2 border-b">Start Time</th>
                        <th class="px-4 py-2 border-b">End Time</th>
                        <th class="px-4 py-2 border-b">Note</th>
                        <th class="px-4 py-2 border-b">Duration</th>
                        <th class="px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shifts as $shift)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $shift->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $shift->jam_masuk }}</td>
                            <td class="px-4 py-2 border-b">{{ $shift->jam_pulang }}</td>
                            <td class="px-4 py-2 border-b">{{ $shift->note }}</td>
                            <td class="px-4 py-2 border-b">{{ $shift->selisih }}</td>
                            <td class="px-4 py-2 border-b">
                                <a href="{{ route('shift-code.edit', $shift->id) }}"
                                   class="text-blue-600 hover:text-blue-900">Edit</a>
                                <form action="{{ route('shift-code.destroy', $shift->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus shift ini?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
