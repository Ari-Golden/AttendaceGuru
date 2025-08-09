@extends('layouts.guru')

@section('content')
<div class="max-w-6xl p-6 mx-auto bg-white rounded-lg shadow-md">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">User Data</h2>
        <a href="{{ route('users.create') }}" class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">Tambah User Baru</a>
    </div>

    <!-- Input Pencarian -->
    <div class="mb-4">
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari nama..." 
            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <!-- Tabel Responsif -->
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 shadow-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr class="text-left">
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">ID Guru</th>
                    <th class="px-4 py-2 border">Mata Pelajaran</th>
                    <th class="px-4 py-2 border">Role</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach ($users as $user)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $user->id }}</td>
                    <td class=" px-4 py-2 border">
                     <span class="font-semibold">{{ $user->name }}</span>
                    </td>
                    <td class="px-4 py-2 border">
                        <span>{{ $user->email }}</span> <br>
                        <span class="text-sm text-gray-500">Alamat: {{ $user->alamat }}</span> <br>
                        <span class="text-sm text-gray-500">No. HP: {{ $user->no_whatsapp }}</span>
                    </td>
                    <td class="px-4 py-2 border">{{ $user->id_guru }}</td>
                    <td class="px-4 py-2 border">{{ $user->program_studi }}</td>
                    <td class="px-4 py-2 border">
                        <form action="{{ route('users.assignRole', $user->id) }}" method="POST">
                            @csrf
                            <select name="role" class="px-2 py-1 border rounded focus:outline-none" onchange="this.form.submit()">
                                <option value="" disabled>Pilih Role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-4 py-2 border text-center">
                        <a href="{{ route('users.edit', $user->id) }}" class="px-3 py-1 text-white bg-blue-500 rounded hover:bg-blue-600 transition">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 text-white bg-red-500 rounded hover:bg-red-600 transition">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tampilan Mobile (Card) -->
    <div class="mt-6 md:hidden">
        <input type="text" id="searchInputMobile" placeholder="Cari nama..." 
            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 mb-4">

        <div id="userCardsContainer" class="flex flex-col items-center gap-4">
            @foreach ($users as $user)
            <div class="user-card w-full p-4 border rounded-lg shadow-md bg-gray-50">
                <div class="flex items-center space-x-3">
                    <img src="{{ $user->profile_photo_url }}" class="w-10 h-10 rounded-full">
                    <div>
                        <h3 class="text-base font-semibold user-name">{{ $user->name }}</h3>
                        <p class="text-xs text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-700">
                    <p><strong>ID Guru:</strong> {{ $user->id_guru }}</p>
                    <p><strong>Mata Pelajaran:</strong> {{ $user->program_studi }}</p>
                </div>
                <div class="flex justify-between mt-3">
                    <a href="{{ route('users.edit', $user->id) }}" class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600 transition">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600 transition">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Script Pencarian -->
    <script>
        function searchTable() {
            let input = document.getElementById("searchInput").value.toUpperCase();
            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                let text = row.textContent.toUpperCase();
                row.style.display = text.includes(input) ? "" : "none";
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("searchInputMobile").addEventListener("keyup", function() {
                let input = this.value.toUpperCase();
                let cards = document.querySelectorAll(".user-card");

                cards.forEach(card => {
                    let nameElement = card.querySelector(".user-name");
                    let text = nameElement.textContent.toUpperCase();
                    card.style.display = text.includes(input) ? "" : "none";
                });
            });
        });
    </script>

</div>
@endsection
