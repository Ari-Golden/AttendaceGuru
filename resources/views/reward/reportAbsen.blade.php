<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Attendance Guru App') }}</title>
    <link rel="icon" href="{{ asset('images/Logo300.png') }}" type="image/x-icon">

    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#007bff">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>

<body class="font-sans antialiased">
    <div class="mt-4 mb-4 text-center">
        <h1 class="text-2xl font-bold">Laporan Reward Absen Guru SMK PGRI Talagasari Karawang</h1>
    </div>

    <div class="flex justify-center mt-4 mb-4 text-center text-sm font-bold ">
        @if(isset($noDataMessage))
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg text-center">
            <p>{{ $noDataMessage }}</p>
        </div>
        @else
        <table class="w-full border border-gray-200 md:table">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Tgl Absen</th>
                    <th class="px-4 py-2 border">Masuk</th>
                    <th class="px-4 py-2 border">Pulang</th>
                    <th class="px-4 py-2 border">Persentase</th>
                    <th class="px-4 py-2 border">Reward Transport</th>
                </tr>
            </thead>
            <tbody>
                @php
                $totalTransportReward = 0;
                @endphp
                @foreach ($rewardData as $data)
                @php
                $totalTransportReward += $data['transportReward'];
                @endphp
                <tr class="border-t">
                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border">
                        {{ $data['reward']->id_user }} | {{ $data['reward']->nama_guru }} <br>
                       
                        <span class="text-sm font-bold text-red-600 textcenter">
                        Masuk {{$data['reward']->standar_masuk ?? 'Belum tersedia' }} -
                        Pulang {{$data['reward']->standar_pulang ?? 'Belum tersedia' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border">{{ $data['reward']->tgl_absen }}</td>
                    <td class="px-4 py-2 border">
                        <div class="flex justify-center">
                            <span class="text-sm font-bold text-center">
                                aktual absen :{{ $data['reward']->jam_masuk }} <br>
                                selisih keterlambatan : <br>
                                {{ ceil($data['diffMasuk'] ?? 0) }} Menit
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-2 border">
                        <div class="flex justify-center">
                            <span class="text-sm font-bold text-center">
                                aktual absen :{{ $data['reward']->jam_pulang }} <br>
                                selisih keterlambatan : <br>
                                {{ ceil($data['diffPulang'] ?? 0) }} Menit
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-2 border">
                         {{ $data['percentage'] }}%
                    </td>
                    <td class="px-4 py-2 border">Rp. {{ number_format($data['transportReward'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-100 font-bold">
                <tr>
                    <td colspan="6" class="px-4 py-2 border text-right">Total Reward Transport:</td>
                    <td class="px-4 py-2 border">Rp. {{ number_format($totalTransportReward, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        @endif
    </div>
</body>

</html>