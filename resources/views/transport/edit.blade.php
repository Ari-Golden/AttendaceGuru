@extends('layouts.guru')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-semibold mb-6">Edit Tunjangan</h2>

    <form action="{{ route('transport.update', $tunjangan->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PATCH') <!-- Tambahkan ini agar Laravel mengenali PATCH -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Tunjangan</label>
            <input type="text" name="name" value="{{ $tunjangan->name }}" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
        </div>
        

        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>{{ $tunjangan->description }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
            <input type="number" name="amount" value="{{ $tunjangan->amount }}" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tipe</label>
            <select name="type" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                <option value="Tetap" {{ $tunjangan->type == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                <option value="Sementara" {{ $tunjangan->type == 'Sementara' ? 'selected' : '' }}>Sementara</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 p-2 w-full border rounded-lg focus:ring focus:ring-blue-300">
                <option value="Aktif" {{ $tunjangan->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Nonaktif" {{ $tunjangan->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">Simpan Perubahan</button>
            <a href="{{ route('transport.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Batal</a>
        </div>
    </form>
</div>
@endsection
