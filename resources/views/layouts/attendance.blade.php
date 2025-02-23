<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Attendance Guru App') }}</title>
    <link rel="icon" href="{{ asset('images/Logo300.png') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Manifest untuk PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <meta name="theme-color" content="#007bff">

    <!-- Favicon untuk PWA -->
    <link rel="icon" href="{{ asset('images/Logo300.png') }}" type="image/x-icon">
</head>

<body class="font-sans antialiased">

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white shadow dark:bg-gray-800">
            <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    @include('layouts.naviAttendance')
    <!-- Page Content -->
    <main>
        @yield('content')
    </main>


    {{-- Disini bisa ditambahkan footer jika diperlukan --}}
    <div class="fixed bottom-0 left-0 right-0 bg-blue-800 md:hidden">
        <div class="flex justify-around py-2">
            <a href="/guru" class="flex flex-col items-center text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7m-9 9v-6a2 2 0 012-2h4a2 2 0 012 2v6m-6 0h6"></path>
                </svg>
                <span class="text-xs">Home</span>
            </a>
            <a href="/profile" class="flex flex-col items-center text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A4 4 0 0112 20a4 4 0 016.879-2.196M15 11a4 4 0 10-8 0 4 4 0 008 0z"></path>
                </svg>
                <span class="text-xs">Profile</span>
            </a>
        </div>
    </div>

    <!-- Scripts tambahan untuk PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(function(registration) {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch(function(error) {
                    console.log('Service Worker registration failed:', error);
                });
        }
    </script>
</body>

</html>
