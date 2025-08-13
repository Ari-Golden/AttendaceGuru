<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Karyawan Belum Absen Hari Ini') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($nonAttendingUsers->isEmpty())
                        <p>Semua karyawan sudah absen hari ini!</p>
                    @else
                        <h3 class="text-lg font-medium mb-4">Daftar Karyawan Belum Absen:</h3>
                        <ul class="list-disc pl-5">
                            @foreach ($nonAttendingUsers as $user)
                                <li>{{ $user->name }} ({{ $user->email }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>