@extends('layouts.guru')

@section('content')
    <div class="max-w-4xl p-6 mx-auto bg-white rounded-lg shadow-md">
        <h2 class="mb-6 text-2xl font-bold text-gray-800">Shift Codes</h2>

        <div class="mb-4">
            <a href="{{ route('shift-code.create') }}" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-700">
                + Tambah Shift Code
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="hidden w-full border border-gray-200 sm:table">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">Shift Code</th>
                        <th class="px-4 py-2 border">Start Time</th>
                        <th class="px-4 py-2 border">End Time</th>
                        <th class="px-4 py-2 border">Note</th>
                        <th class="px-4 py-2 border">Duration</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shifts as $shift)
                        <tr>
                            <td class="px-4 py-2 border">{{ $shift->id }}</td>
                            <td class="px-4 py-2 border">{{ $shift->jam_masuk }}</td>
                            <td class="px-4 py-2 border">{{ $shift->jam_pulang }}</td>
                            <td class="px-4 py-2 border">{{ $shift->note }}</td>
                            <td class="px-4 py-2 border">{{ $shift->selisih }}</td>
                            <td class="px-4 py-2 border">
                                <a href="{{ route('shift-code.edit', $shift->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                <form action="{{ route('shift-code.destroy', $shift->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus shift ini?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Tampilan Mobile -->
            <div class="sm:hidden">
                @foreach ($shifts as $shift)
                <div class="p-4 mb-4 bg-gray-100 rounded-lg shadow-md">
                    <p class="text-lg font-bold">Shift Code: {{ $shift->id }}</p>
                    <p class="text-gray-700"><strong>Start:</strong> {{ $shift->jam_masuk }}</p>
                    <p class="text-gray-700"><strong>End:</strong> {{ $shift->jam_pulang }}</p>
                    <p class="text-gray-700"><strong>Note:</strong> {{ $shift->note }}</p>
                    <p class="text-gray-700"><strong>Duration:</strong> {{ $shift->selisih }}</p>
                    <div class="mt-2">
                        <a href="{{ route('shift-code.edit', $shift->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        |
                        <form action="{{ route('shift-code.destroy', $shift->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
