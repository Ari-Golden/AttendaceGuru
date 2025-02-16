@extends('layouts.guru')
@section('content')
    <div class="max-w-full p-6 mx-auto bg-white rounded shadow-md">
        <h2 class="mb-4 text-2xl font-bold">Daftar Absensi Guru</h2>
        <!-- Filter dan Pencarian -->
        <div class="flex flex-col justify-between mb-6 md:flex-row">
            <!-- Filter -->
            <div class="flex items-center mb-4 md:mb-0">
                <label for="statusFilter" class="mr-2 font-medium">Filter Status:</label>
                <select id="statusFilter"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="masuk">Masuk</option>
                    <option value="pulang">Pulang</option>
                </select>
            </div>
            <!-- Pencarian -->
            <div class="relative w-full md:w-1/3">
                <input type="text" id="searchInput" placeholder="Cari nama guru..."
                    class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute w-5 h-5 text-gray-400 transform -translate-y-1/2 left-3 top-1/2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        <!-- Tabel Data Absensi -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama Guru
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">NIK Guru
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Mapel
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Foto
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal
                            Absen</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jam Absen
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Lokasi
                            Absen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($absensi as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->nama_guru }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->id_guru }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->mapel }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="{{ $item->status === 'masuk' ? 'text-green-600' : 'text-red-600' }} font-medium">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ $item->foto_selfie }}" alt="Foto Absen" class="w-10 h-10 rounded-full">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tgl_absen)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->jam_absen }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->lokasi_absen }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="mt-6">
            {{ $absensi->links() }}
        </div>
    </div>

    <!-- JavaScript untuk Filter dan Pencarian -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');

            // Fungsi untuk memperbarui URL dengan parameter filter
            function updateUrlParams() {
                const statusValue = statusFilter.value;

                const url = new URL(window.location.href);
                if (statusValue && statusValue !== 'semua') {
                    url.searchParams.set('status', statusValue);
                } else {
                    url.searchParams.delete('status');
                }

                window.location.href = url.toString();
            }

            // Event listener untuk filter status
            statusFilter.addEventListener('change', function() {
                updateUrlParams();
            });
        });
    </script>
@endsection
