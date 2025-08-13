@extends('layouts.attendance')

@section('content')
    <div class="flex flex-col h-screen justify-between">
        <main class="flex-grow">
            <div class="p-4">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 mb-4">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Selamat Datang, {{ Auth::user()->name }}!</h1>
                    <p class="text-gray-600 dark:text-gray-400">Selamat datang di dasbor guru Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Absensi Hari Ini</h2>
                        <p class="text-gray-600 dark:text-gray-400">Lihat dan kelola absensi harian Anda.</p>
                        <a href="{{ route('attendanceview') }}" class="text-blue-500 hover:underline mt-2 inline-block">Lihat Detail</a>
                    </div>
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Absensi PKL</h2>
                        <p class="text-gray-600 dark:text-gray-400">Lacak dan kelola absensi PKL.</p>
                        <a href="{{ route('attendancePkl') }}" class="text-blue-500 hover:underline mt-2 inline-block">Lihat Detail</a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 mt-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Record Absensi Hari Ini</h2>
                    <div class="mt-4">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Jam Absen
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendance as $item)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">
                                            {{ $item->jam_absen }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $item->status }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4">Belum ada data absensi untuk hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        
    </div>
@endsection
