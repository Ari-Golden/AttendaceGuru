@extends('layouts.guru')

@section('content')
    <div class="max-w-6xl p-6 mx-auto bg-white rounded-lg shadow-md">
        <h2 class="mb-6 text-2xl font-bold text-gray-800">User Data</h2>

        <!-- Tabel Responsif (Desktop) -->
        <div class="hidden overflow-x-auto md:block">
            <div class="mb-4">
                <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for names.." class="w-full px-4 py-2 border rounded">
            </div>

            <script>
                function searchTable() {
                    var input, filter, table, tr, td, i, txtValue;
                    input = document.getElementById("searchInput");
                    filter = input.value.toUpperCase();
                    table = document.querySelector("table");
                    tr = table.getElementsByTagName("tr");

                    for (i = 1; i < tr.length; i++) {
                        tr[i].style.display = "none";
                        td = tr[i].getElementsByTagName("td");
                        for (var j = 0; j < td.length; j++) {
                            if (td[j]) {
                                txtValue = td[j].textContent || td[j].innerText;
                                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                    tr[i].style.display = "";
                                    break;
                                }
                            }
                        }
                    }
                }
            </script>
            <table class="w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Name</th>
                        <th class="px-4 py-2 border">Email</th>
                        <th class="px-4 py-2 border">Id Guru</th>
                        <th class="px-4 py-2 border">Mata Pelajaran</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-t">
                            <td class="px-4 py-2 border">{{ $user->id }}</td>
                            <td class="flex items-center gap-2 px-4 py-2 border">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                    class="w-8 h-8 rounded-full">
                                <span class="font-bold">{{ $user->name }}</span>
                            </td>
                            <td class="px-4 py-2 border">
                                <span>{{ $user->email }}</span> <br>
                                Alamat: {{ $user->alamat }} <br>
                                No. HP: {{ $user->no_whatsapp }}
                            </td>
                            <td class="px-4 py-2 border">{{ $user->id_guru }}</td>
                            <td class="px-4 py-2 border">{{ $user->program_studi }}</td>
                            <td class="px-4 py-2 text-center border">
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="px-3 py-1 text-white bg-blue-500 rounded hover:bg-blue-600">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 text-white bg-red-500 rounded hover:bg-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

       <!-- Tampilan Mobile: Menggunakan Card -->
<div class="flex flex-col items-center gap-4 md:hidden">
    <!-- Input pencarian -->
    <div class="w-11/12 max-w-xs mb-4">
        <input type="text" id="searchInputMobile" placeholder="Search for names..."
            class="w-full px-4 py-2 border rounded">
    </div>

    <!-- Daftar user -->
    <div id="userCardsContainer" class="flex flex-col items-center w-full gap-4">
        @foreach ($users as $user)
            <div class="user-card w-11/12 max-w-xs p-4 border rounded-lg shadow-md bg-gray-50 h-auto min-h-[150px] flex flex-col justify-between">
                <div class="flex items-center space-x-3">
                    <img src="{{ $user->profile_photo_url }}" class="w-10 h-10 rounded-full">
                    <div>
                        <h3 class="text-base font-semibold user-name">{{ $user->name }}</h3>
                        <p class="text-xs text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="mt-2 text-sm">
                    <p><strong>Id Guru:</strong> {{ $user->id_guru }}</p>
                    <p><strong>Mata Pelajaran:</strong> {{ $user->program_studi }}</p>
                </div>
                <div class="flex justify-between mt-3">
                    <a href="{{ route('users.edit', $user->id) }}"
                        class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("searchInputMobile").addEventListener("keyup", function () {
            var input = this.value.toUpperCase();
            var cards = document.querySelectorAll(".user-card");

            cards.forEach(function (card) {
                var nameElement = card.querySelector(".user-name");
                var txtValue = nameElement ? nameElement.textContent || nameElement.innerText : "";

                if (txtValue.toUpperCase().indexOf(input) > -1) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
</script>

    </div>
@endsection
