{{-- menampilkan data reward --}}
@extends('layouts.guru')

@section('content')
<div class="bg-gray-100 p-6 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Laporan Reward Absensi Guru</h1>

        <!-- Input Periode Tutup Buku -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Filter Periode Absensi</h3>
            <form action="{{ route('reward') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label for="from_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" id="from_date" name="from_date" value="{{ request('from_date') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="until_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" id="until_date" name="until_date" value="{{ request('until_date') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Filter Data
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Data Reward -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Data Reward Absensi</h2>

            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="flex justify-between items-center mb-4">
                <div class="flex space-x-2">
                    <a href="{{ route('reportTunjanganExcel', request()->query()) }}"
                        class="px-4 py-2 text-white bg-green-500 rounded-md hover:bg-green-600 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download Excel
                    </a>
                    <a href="{{ route('reportTunjanganPdf', request()->query()) }}" target="_blank"
                        class="px-4 py-2 text-white bg-red-500 rounded-md hover:bg-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v2m14 0h-2"></path></svg>
                        View PDF
                    </a>
                </div>
                <div class="relative w-1/3">
                    <input type="text" name="search" placeholder="Cari nama guru..." value="{{ request('search') }}"
                        class="w-full px-3 py-2 pl-10 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            @if(isset($noDataMessage))
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg text-center mb-4">
                <p>{{ $noDataMessage }}</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Guru</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Absen Terakhir</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk (Aktual/Standar)</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Pulang (Aktual/Standar)</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase Kehadiran</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reward Transport</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($rewardData as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data['reward']->id_user }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $data['reward']->nama_guru }} <br>
                                <span class="text-xs text-gray-500">({{ $data['reward']->mapel }})</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($data['reward']->tgl_absen)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Aktual: {{ $data['reward']->jam_masuk ?? 'N/A' }} <br>
                                Standar: {{ $data['reward']->standar_masuk ?? 'N/A' }} <br>
                                <span class="text-xs text-red-500">Terlambat: {{ ceil($data['diffMasuk'] ?? 0) }} Menit</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Aktual: {{ $data['reward']->jam_pulang ?? 'N/A' }} <br>
                                Standar: {{ $data['reward']->standar_pulang ?? 'N/A' }} <br>
                                <span class="text-xs text-red-500">Pulang Cepat: {{ ceil($data['diffPulang'] ?? 0) }} Menit</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data['percentage'] }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                Rp. {{ number_format($data['transportReward'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="6" class="px-6 py-3 text-right text-sm font-bold text-gray-700 uppercase">Total Reward Transport Keseluruhan:</td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-gray-900">
                                Rp. {{ number_format(array_sum(array_column($rewardData, 'transportReward')), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif

            <!-- Tampilan Mobile (Optional: can be refined further) -->
            <div class="grid gap-4 md:hidden mt-6">
                @forelse ($rewardData as $data)
                <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $data['reward']->nama_guru }}</h3>
                    <p class="text-sm text-gray-600 mb-1">ID: {{ $data['reward']->id_user }}</p>
                    <p class="text-sm text-gray-600 mb-1">Tanggal Absen: {{ \Carbon\Carbon::parse($data['reward']->tgl_absen)->translatedFormat('d F Y') }}</p>
                    <p class="text-sm text-gray-600 mb-1">Jam Masuk: {{ $data['reward']->jam_masuk ?? 'N/A' }} (Standar: {{ $data['reward']->standar_masuk ?? 'N/A' }})</p>
                    <p class="text-sm text-gray-600 mb-1">Jam Pulang: {{ $data['reward']->jam_pulang ?? 'N/A' }} (Standar: {{ $data['reward']->standar_pulang ?? 'N/A' }})</p>
                    <p class="text-sm text-gray-600 mb-1">Persentase: {{ $data['percentage'] }}%</p>
                    <p class="text-sm font-bold text-gray-800">Reward: Rp. {{ number_format($data['transportReward'], 0, ',', '.') }}</p>
                </div>
                @empty
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg text-center">
                    <p>Tidak ada data reward yang ditemukan untuk periode ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
