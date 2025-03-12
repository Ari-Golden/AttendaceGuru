<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Attendance Guru App') }}</title>
    <link rel="icon" href="{{ asset('images/Logo300.png') }}" type="image/x-icon">
    {{-- ini map --}}

    
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#007bff">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen pt-16 bg-gray-100 dark:bg-gray-900">
        @include('layouts.naviguru')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow dark:bg-gray-800">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
    @stack('scripts')
    {{-- disini ada footer --}}
    @include('layouts.footer')
</body>

</html>
