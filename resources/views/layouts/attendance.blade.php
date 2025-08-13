<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aplikasi Absensi') }}</title>
   
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Manifest untuk PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <meta name="theme-color" content="#007bff">

    <!-- Favicon untuk PWA -->
    <link rel="icon" href="{{ asset('images/logo300.png') }}" type="image/x-icon">
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="flex flex-col h-screen">
        <header class="bg-blue-600 dark:bg-blue-800 shadow-md text-white">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo150.png') }}" alt="Logo" class="h-8 w-auto mr-2">
                    <h1 class="text-xl font-semibold">{{ $title ?? 'Aplikasi Absensi' }}</h1>
                </div>
                <div>
                    <a href="{{ route('profile.edit') }}" class="text-white hover:text-gray-200">
                        @if (Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="w-8 h-8 object-cover rounded-full">
                    @else
                        <img src="{{ asset('images/default-profile.jpg') }}" alt="Default Profile Picture" class="w-8 h-8 object-cover rounded-full">
                    @endif
                    </a>
                </div>
            </div>
        </header>

        <main class="flex-grow overflow-y-auto pb-16">
            @yield('content')
        </main>

        <footer class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 shadow-md">
            <div class="grid grid-cols-4">
                <a href="{{ route('guru.dashboard') }}" class="text-center p-4 {{ request()->routeIs('guru.dashboard') ? 'text-blue-500' : 'text-gray-600 dark:text-gray-400' }}">
                    <i class="fas fa-home text-2xl"></i>
                    <span class="block text-xs">Home</span>
                </a>
                <a href="{{ route('attendanceview') }}" class="text-center p-4 {{ request()->routeIs('attendanceview') ? 'text-blue-500' : 'text-gray-600 dark:text-gray-400' }}">
                    <i class="fas fa-calendar-check text-2xl"></i>
                    <span class="block text-xs">Absen Harian</span>
                </a>
                <a href="{{ route('attendancePkl') }}" class="text-center p-4 {{ request()->routeIs('attendancePkl') ? 'text-blue-500' : 'text-gray-600 dark:text-gray-400' }}">
                    <i class="fas fa-user-clock text-2xl"></i>
                    <span class="block text-xs">Absen PKL</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="text-center p-4 text-gray-600 dark:text-gray-400">
                    @csrf
                    <button type="submit" class="w-full">
                        <i class="fas fa-sign-out-alt text-2xl"></i>
                        <span class="block text-xs">Keluar</span>
                    </button>
                </form>
            </div>
        </footer>
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
