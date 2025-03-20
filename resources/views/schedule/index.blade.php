@extends('layouts.guru')

@section('content')
<div class="max-w-6xl p-6 mx-auto bg-white rounded-lg shadow-md">
    <h2 class="mb-6 text-2xl font-bold text-gray-800">Jadwal Shift Guru</h2>

    @if (session('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between mb-4">
        <a href="{{ route('shift-schedule.create') }}" class="px-4 py-2 text-white bg-green-500 rounded-md hover:bg-green-700">Create Schedule</a>
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for names.." class="px-4 py-2 border rounded-md">
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

    <div class="overflow-x-auto">
        <table class="hidden w-full border border-gray-200 md:table">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">User ID</th>
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
                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border">{{ $schedule->id }}</td>
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

        <!-- Tampilan Mobile -->
        <div class="flex justify-between mb-4 md:hidden">
            <a href="{{ route('shift-schedule.create') }}" class="px-4 py-2 text-white bg-green-500 rounded-md hover:bg-green-700">Create Schedule</a>
            <input type="text" id="mobileSearchInput" onkeyup="searchMobile()" placeholder="Search for names.." class="px-4 py-2 border rounded-md">
        </div>

        <div class="grid gap-4 md:hidden">
            @foreach ($schedules as $schedule)
            <div class="p-4 border rounded-lg shadow-md">
            <p class="text-lg font-semibold text-gray-700">{{ $schedule->nama_guru }}</p>
            <p class="text-sm text-gray-500">Shift: <span class="font-bold">{{ $schedule->shift_code }}</span></p>
            <p class="text-sm text-gray-500">Catatan: {{ $schedule->shift_note }}</p>
            <p class="text-sm text-gray-500">Jam Masuk: <span class="font-bold">{{ $schedule->jam_masuk }}</span></p>
            <p class="text-sm text-gray-500">Jam Pulang: <span class="font-bold">{{ $schedule->jam_pulang }}</span></p>
            <div class="flex gap-2 mt-3">
                <a href="{{ route('shift-schedule.edit', $schedule->id) }}" class="px-3 py-1 text-white bg-blue-500 rounded-md hover:bg-blue-700">Edit</a>
                <form action="{{ route('shift-schedule.destroy', $schedule->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1 text-white bg-red-500 rounded-md hover:bg-red-700">Hapus</button>
                </form>
            </div>
            </div>
            @endforeach
        </div>

        <script>
        function searchMobile() {
            var input, filter, div, divs, p, i, txtValue;
            input = document.getElementById("mobileSearchInput");
            filter = input.value.toUpperCase();
            div = document.querySelector(".grid");
            divs = div.getElementsByTagName("div");

            for (i = 0; i < divs.length; i++) {
            p = divs[i].getElementsByTagName("p")[0];
            if (p) {
                txtValue = p.textContent || p.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                divs[i].style.display = "";
                } else {
                divs[i].style.display = "none";
                }
            }
            }
        }
        </script>
    </div>
</div>
@endsection
