@extends('layouts.guru')
@section('content')
    <div class="max-w-full p-4 mx-auto bg-white rounded shadow-md">
        <h2 class="mb-4 text-xl font-bold md:text-2xl">Daftar Absensi Guru</h2>

        <!-- Filter dan Pencarian -->
        <div class="flex flex-col items-start justify-between mb-4 space-y-3 md:flex-row md:space-y-0">
            <!-- Filter -->
            <div class="flex items-center">
                <label for="statusFilter" class="mr-2 text-sm font-medium">Filter Status:</label>
                <select id="statusFilter"
                    class="px-3 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="masuk">Masuk</option>
                    <option value="pulang">Pulang</option>
                </select>
            </div>
            <!-- Pencarian -->
            <div class="relative w-full md:w-1/3">
                <input type="text" id="searchInput" placeholder="Cari data absen..."
                    class="w-full px-3 py-1 pl-8 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute w-4 h-4 text-gray-400 transform -translate-y-1/2 left-2 top-1/2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('searchInput');
                    const tableRows = document.querySelectorAll('tbody tr');

                    searchInput.addEventListener('input', function() {
                        const searchTerm = searchInput.value.toLowerCase();

                        tableRows.forEach(row => {
                            const rowText = row.textContent.toLowerCase();
                            if (rowText.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    });
                });
            </script>
        </div>

        <!-- Tabel Data Absensi -->
        <div class="overflow-x-auto overflow-y-auto max-h-96">
            <table class="min-w-full text-xs bg-white border border-gray-200 divide-y divide-gray-200 md:text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-500 uppercase">No</th>
                        <th class="px-3 py-2 text-left text-gray-500 uppercase">Nama</th>                                            
                        <th class="px-3 py-2 text-left text-gray-500 uppercase">Status</th>
                        <th class="px-3 py-2 text-left text-gray-500 uppercase">Foto</th>
                        <th class="hidden px-3 py-2 text-left text-gray-500 uppercase md:table-cell">Tanggal</th>
                        <th class="hidden px-3 py-2 text-left text-gray-500 uppercase md:table-cell">Jam Standard Absen</th>
                        <th class="hidden px-3 py-2 text-left text-gray-500 uppercase md:table-cell">Jam Absen</th>
                        <th class="hidden px-3 py-2 text-left text-gray-500 uppercase md:table-cell">Keterlambatan</th>
                        <th class="hidden px-3 py-2 text-left text-gray-500 uppercase md:table-cell">Lokasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($absensi as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">{{ $loop->iteration }}</td>
                            <td class="px-3 py-2">{{ $item->nama_guru }}</td> 
                            <td class="px-3 py-2">
                                <span
                                    class="{{ $item->status === 'masuk' ? 'text-green-600' : 'text-red-600' }} font-medium">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-2">
                            <img src="{{ asset('storage/' . $item->foto_selfie) }}" alt="Foto Absen" class="w-8 h-8 rounded-full">

                            </td>
                            <td class="hidden px-3 py-2 md:table-cell">
                                {{ \Carbon\Carbon::parse($item->tgl_absen)->format('d/m/Y') }}
                            </td>
                            <td class="hidden px-3 py-2 md:table-cell">{{ $item->jam_masuk }} - {{ $item->jam_pulang }}</td>
                            <td class="hidden px-3 py-2 md:table-cell">{{ $item->jam_absen }}</td>
                            <td class="hidden px-3 py-2 md:table-cell">{{ $item->keterlambatan }} Menit</td>
                            <td class="hidden px-3 py-2 md:table-cell">{{ $item->lokasi_absen }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $absensi->links() }}
        </div>
    </div>

    <!-- JavaScript untuk Filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');

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

            statusFilter.addEventListener('change', updateUrlParams);
        });
    </script>
@endsection
