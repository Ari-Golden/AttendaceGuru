@extends('layouts.guru')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-semibold mb-6">List Tunjangan</h2>
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <!-- Form Tambah Tunjangan -->
    <div class="bg-gray-100 p-6 rounded-lg mb-6 h-[40vh] overflow-auto">
    <h3 class="text-lg font-semibold mb-4">Tambah Data Tunjangan</h3>
    <form action="{{ route('transport.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Tunjangan</label>
                <input type="text" name="name" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                <input type="number" name="amount" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tipe</label>
                <select name="type" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="Tetap">Tetap</option>
                    <option value="Sementara">Sementara</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="Aktif">Aktif</option>
                    <option value="Nonaktif">Nonaktif</option>
                </select>
            </div>
        </div>

        <!-- Textarea Deskripsi di Baris Terakhir -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required></textarea>
        </div>

        <div class="mt-4 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">
                Simpan
            </button>
        </div>
    </form>
</div>


    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-200 rounded-lg text-left">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="p-3 border border-gray-300">ID</th>
                    <th class="p-3 border border-gray-300">Nama Tunjangan</th>
                    <th class="p-3 border border-gray-300">Deskripsi</th>
                    <th class="p-3 border border-gray-300">Jumlah</th>
                    <th class="p-3 border border-gray-300">Tipe</th>
                    <th class="p-3 border border-gray-300">Status</th>
                    <th class="p-3 border border-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tunjangans as $tunjangan)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="p-3 border border-gray-300">{{ $tunjangan->id }}</td>
                    <td class="p-3 border border-gray-300">{{ $tunjangan->name }}</td>
                    <td class="p-3 border border-gray-300">{{ $tunjangan->description }}</td>
                    <td class="p-3 border border-gray-300">{{ $tunjangan->amount }}</td>
                    <td class="p-3 border border-gray-300">{{ $tunjangan->type }}</td>
                    <td class="p-3 border border-gray-300">
                        <span class="px-3 py-1 text-sm font-semibold {{ $tunjangan->status == 'Aktif' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }} rounded-md">
                            {{ $tunjangan->status }}
                        </span>
                    </td>
                    <td class="p-3 border border-gray-300 flex space-x-2">
                        <a href="{{ route('transport.edit', $tunjangan->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition text-sm">
                            Edit
                        </a>

                        <form action="{{ route('transport.destroy', $tunjangan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tunjangan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition text-sm">
                                Hapus
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