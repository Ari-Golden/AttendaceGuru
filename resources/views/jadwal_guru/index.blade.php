@extends('layouts.guru')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Jadwal Guru</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('jadwal-guru.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Jadwal</a>

    <div class="overflow-x-auto">
        <div class="mb-4">
            <input type="text" id="searchInput" placeholder="Cari Nama Guru..." class="border border-gray-300 rounded py-2 px-4 mb-4 w-full">
        </div>
        <script>
            document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(function(row) {
                var name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                if (name.includes(searchValue)) {
                row.style.display = '';
                } else {
                row.style.display = 'none';
                }
            });
            });
        </script>
        <table class="min-w-full border border-gray-300 bg-white shadow-md rounded-lg">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">ID User</th>
                    <th class="px-4 py-2 border">Nama Guru</th>
                    <th class="px-4 py-2 border">Senin</th>
                    <th class="px-4 py-2 border">Selasa</th>
                    <th class="px-4 py-2 border">Rabu</th>
                    <th class="px-4 py-2 border">Kamis</th>
                    <th class="px-4 py-2 border">Jumat</th>
                    <th class="px-4 py-2 border">Sabtu</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 text-center">
                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border">{{ $user->id }}</td>
                    <td class="px-4 py-2 border font-semibold text-left">{{ $user->name }}</td>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                        @php
                            $jadwal = $user->jadwal ? $user->jadwal->filter(function($j) use ($hari) {
                                return $j->hari === $hari;
                            })->first() : null;
                        @endphp
                        <td class="px-4 py-2 border">
                            @if($jadwal)
                            <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">
                                <span class="text-sm font-medium"> {{ $jadwal->jam_masuk ?? '-' }} - {{ $jadwal->jam_pulang ?? '-' }}</span>
                                <div class="mt-1 space-x-2">
                                    <a href="{{ route('jadwal-guru.edit', $jadwal->id_jadwal) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">Edit</a>
                                    <form action="{{ route('jadwal-guru.destroy', $jadwal->id_jadwal) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                    @endforeach
                    <td class="px-4 py-2 border">
                        <a href="{{ route('jadwal-guru.create.id', ['id' => $user->id]) }}" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs">Tambah</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
