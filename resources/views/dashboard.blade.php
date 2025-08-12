@extends('layouts.guru')

@section('content')
<div class="bg-gray-100 p-6">
    <div class="max-w-full mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Absensi Guru</h1>

        <!-- Statistik Cards -->
        <div class="flex items-center justify-between space-x-[2px] mb-6">
            <!-- Card 1: Total Data -->
            <div class="bg-white p-4 rounded-lg shadow-md flex items-center space-x-3 w-1/4">
                <div class="bg-blue-500 p-3 rounded-full">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v2m14 0h-2" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Data</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalData }}</p>
                </div>
            </div>

            <!-- Card 2: Karyawan Masuk -->
            <div class="bg-white p-4 rounded-lg shadow-md flex items-center space-x-3 w-1/4">
                <div class="bg-green-500 p-3 rounded-full">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Karyawan Masuk</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalMasuk }}</p>
                </div>
            </div>

            <!-- Card 3: Karyawan Pulang -->
            <div class="bg-white p-4 rounded-lg shadow-md flex items-center space-x-3 w-1/4">
                <div class="bg-red-500 p-3 rounded-full">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H3m14 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Karyawan Pulang</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPulang }}</p>
                </div>
            </div>

            <!-- Card 4: Belum Absen -->
            <div class="bg-white p-4 rounded-lg shadow-md flex items-center space-x-3 w-1/4">
                <div class="bg-yellow-500 p-3 rounded-full">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Belum Absen Hari Ini</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalBelumAbsen }}</p>
                </div>
            </div>
        </div>

        <!-- Filter dan Pencarian -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-6">
            <form action="{{ route('dashboard') }}" method="GET">
                <div class="flex items-baseline space-x-2">
                    <!-- Filter Status -->
                    <select name="status" class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="masuk" {{ request('status') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="pulang" {{ request('status') == 'pulang' ? 'selected' : '' }}>Pulang</option>
                    </select>

                    <!-- Filter Tanggal -->
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" title="Dari Tangal">
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" title="Sampai Tanggal">

                    <!-- Pencarian -->
                    <div class="relative">
                        <input type="text" name="search" placeholder="Cari..."
                            class="px-3 py-2 pl-10 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                            value="{{ request('search') }}">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Filter</button>
                </div>
            </form>
        </div>

        <!-- Pagination -->
        <div class="mb-4">
            {{ $absensi->links() }}
        </div>

        <!-- Tabel Data Absensi -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('dashboard', array_merge(request()->query(), ['sort' => 'nama_guru', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                    Nama
                                    @if(request('sort') == 'nama_guru')
                                        @if(request('direction') == 'asc')
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                        @else
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('dashboard', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                    Status
                                    @if(request('sort') == 'status')
                                        @if(request('direction') == 'asc')
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                        @else
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('dashboard', array_merge(request()->query(), ['sort' => 'tgl_absen', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                    Tanggal
                                    @if(request('sort') == 'tgl_absen')
                                        @if(request('direction') == 'asc')
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                        @else
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('dashboard', array_merge(request()->query(), ['sort' => 'jam_masuk', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                    Jam Standard
                                    @if(request('sort') == 'jam_masuk')
                                        @if(request('direction') == 'asc')
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                        @else
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('dashboard', array_merge(request()->query(), ['sort' => 'jam_absen', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                    Jam Absen
                                    @if(request('sort') == 'jam_absen')
                                        @if(request('direction') == 'asc')
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                        @else
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('dashboard', array_merge(request()->query(), ['sort' => 'keterlambatan', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                    Keterlambatan
                                    @if(request('sort') == 'keterlambatan')
                                        @if(request('direction') == 'asc')
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                        @else
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('dashboard', array_merge(request()->query(), ['sort' => 'lokasi_absen', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                    Lokasi
                                    @if(request('sort') == 'lokasi_absen')
                                        @if(request('direction') == 'asc')
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                        @else
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($absensi as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama_guru }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="{{ asset('storage/' . $item->foto_selfie) }}" alt="Foto Absen" class="w-10 h-10 rounded-full">
                                </td>
                                <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->tgl_absen)->translatedFormat('d F Y') }}
                                </td>
                                <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->jam_masuk }} - {{ $item->jam_pulang }}</td>
                                <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->jam_absen }}</td>
                                <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->keterlambatan }} Menit</td>
                                <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->lokasi_absen }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('absensi.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Tidak ada data yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $absensi->links() }}
        </div>
    </div>
</div>
@endsection