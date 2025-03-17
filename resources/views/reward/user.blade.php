@extends('layouts.attendance')
@section('content')

@if(session('error'))
    <div class="p-4 mb-4 text-red-800 bg-red-200 border-l-4 border-red-600">
        {{ session('error') }}
    </div>
@endif

<div class="mt-20 flex-auto max-w-6xl p-6 mx-auto mb-4 bg-white rounded-lg shadow-md">
    <div class="p-4 rounded-lg shadow-md">
        <h3 class="mb-4 text-lg font-semibold text-gray-800">filter tanggal</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="from_date" class="block mb-2 text-sm font-medium text-gray-700">From Date</label>
                <input type="date" id="from_date" name="from_date"
                    class="block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label for="until_date" class="block mb-2 text-sm font-medium text-gray-700">Until Date</label>
                <input type="date" id="until_date" name="until_date"
                    class="block w-full p-2 border border-gray-300 rounded-md">
            </div>
        </div>
        <div class="flex justify-end">
            <button onclick="filterByDate()"
                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Filter</button>
        </div>
    </div>
</div>

<script>
    function filterByDate() {
        const fromDate = document.getElementById('from_date').value;
        const untilDate = document.getElementById('until_date').value;

        if (fromDate && untilDate) {
            window.location.href = `?from_date=${fromDate}&until_date=${untilDate}`;
        } else {
            alert('Silakan pilih kedua tanggal');
        }
    }
</script>

<div class="max-w-6xl p-6 mx-auto bg-white rounded-lg shadow-md">
    <h2 class="mb-6 text-2xl font-bold text-gray-800">Data Reward</h2>

    <div class="mb-4">
        <label for="searchInput" class="block mb-2 text-sm font-medium text-gray-700">Pencarian</label>
        <input type="text" id="searchInput" onkeyup="searchData()"
            placeholder="Cari Nama, ID Guru, atau Tanggal Absen"
            class="block w-full p-2 border border-gray-300 rounded-md">
    </div>

    <div class="grid gap-4 sm:hidden">
        @foreach ($rewardData as $data)
        <div class="p-4 border rounded-lg shadow-md bg-gray-100">
            <h3 class="text-lg font-semibold">{{ $data['reward']->nama_guru }}</h3>
            <p class="text-sm text-gray-600">ID: {{ $data['reward']->id_guru }}</p>
            <p class="text-sm">Tanggal Absen: {{ $data['reward']->tgl_absen }}</p>
            <p class="text-sm">Masuk: {{ $data['reward']->jam_masuk }} (Selisih: {{ ceil($data['diffMasuk'] ?? 0) }} Menit)</p>
            <p class="text-sm">Pulang: {{ $data['reward']->jam_pulang }} (Selisih: {{ ceil($data['diffPulang'] ?? 0) }} Menit)</p>
            <p class="text-sm font-bold">Persentase: {{ $data['percentage'] }}%</p>
            <p class="text-sm font-bold">Reward: Rp. {{ number_format($data['transportReward'], 0, ',', '.') }}</p>
        </div>
        @endforeach
    </div>

    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Tgl Absen</th>
                    <th class="px-4 py-2 border">Masuk</th>
                    <th class="px-4 py-2 border">Pulang</th>
                    <th class="px-4 py-2 border">Persentase</th>
                    <th class="px-4 py-2 border">Reward Transport</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rewardData as $data)
                <tr class="border-t">
                    <td class="px-4 py-2 border">{{ $data['reward']->id_guru }}</td>
                    <td class="px-4 py-2 border">{{ $data['reward']->nama_guru }}</td>
                    <td class="px-4 py-2 border">{{ $data['reward']->tgl_absen }}</td>
                    <td class="px-4 py-2 border">{{ $data['reward']->jam_masuk }}</td>
                    <td class="px-4 py-2 border">{{ $data['reward']->jam_pulang }}</td>
                    <td class="px-4 py-2 border">{{ $data['percentage'] }}%</td>
                    <td class="px-4 py-2 border">Rp. {{ number_format($data['transportReward'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
