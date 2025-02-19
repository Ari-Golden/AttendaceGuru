{{-- menampilkan data reward --}}
@extends('layouts.guru')
@section('content')
    <div class="max-w-6xl p-6 mx-auto bg-white rounded-lg shadow-md">
        <h2 class="mb-6 text-2xl font-bold text-gray-800">Data Reward</h2>
        @if (session('success'))
            <div class="mb-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif
        <div class="overflow-x-auto">
            <table class="hidden w-full border border-gray-200 md:table">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Nama</th>
                        <th class="px-4 py-2 border">Tgl Absen</th>
                        <th class="px-4 py-2 border">Masuk</th>
                        <th class="px-4 py-2 border">Pulang</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rewards as $reward)
                        <tr class="border-t">
                            <td class="px-4 py-2 border">{{ $reward->id_guru }}</td>
                            <td class="px-4 py-2 border">{{ $reward->nama_guru }}</td>
                            <td class="px-4 py-2 border">{{ $reward->tgl_absen }}</td>
                            <td class="px-4 py-2 border">Absen Masuk {{ $reward->jam_masuk }} --
                                Standard Absen Masuk {{ $schedules->jam_masuk }}
                            </td>
                            <td class="px-4 py-2 border">Absen pulang {{ $reward->jam_pulang }} --
                                Standard Absen pulang {{ $schedules->jam_pulang }}
                            </td>
                            <td class="px-4 py-2 text-center border">
                                <a href="{{ route('reward.edit', $reward->id_guru) }}"
                                    class="text-blue-600 hover:text-blue-800">Edit</a>
                                |
                                <form action="" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Tampilan Mobile -->
            <div class="grid gap-4 md:hidden">
                @foreach ($rewards as $reward)
                    <div class="p-4 border rounded-lg shadow-md">
                        <div class="flex items-center mb-2">
                            <div class="w-12 h-12 mr-4 bg-gray-300 rounded-full"></div>
                            <div>
                                <h3 class="text-lg font-semibold">{{ $reward->nama_guru }}</h3>
                                <p class="text-gray-600">{{ $reward->tgl_absen }}</p>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('reward.edit', $reward->id_guru) }}"
                                class="text-blue-600 hover:text-blue-800">Edit</a>
                            |
                            <form action="" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mt-6">
            <a href="" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Tambah
                Reward</a>
        </div>
    </div>
@endsection
