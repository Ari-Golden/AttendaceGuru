<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to AttendanceGuru</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-blue-50">
    <header class="fixed top-0 left-0 z-50 w-full py-4 shadow-md bg-sky-800">
        <div class="container flex items-center justify-between mx-auto">
            <div class="flex items-center">
                <img src="{{ asset('images/Logo300.png') }}" alt="AttendanceGuru Logo" class="w-12 h-auto mr-3">
                <span class="text-xl font-bold text-blue-400">SMK PGRI TALAGASARI KARAWANG</span>
            </div>
            @if (Route::has('login'))
                <nav class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-4 py-2 text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-4 py-2 text-white transition duration-300 bg-yellow-500 rounded-lg hover:bg-yellow-600">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-lg p-8 text-center bg-white rounded-lg shadow-lg">
            <img src="{{ asset('images/Logo300.png') }}" alt="AttendanceGuru Logo" class="w-32 h-auto mx-auto mb-6">
            <h1 class="mb-4 text-4xl font-bold text-center text-blue-600">Welcome to AttendanceGuru</h1>
            <p class="mb-6 text-lg text-center text-gray-700">Your reliable partner in managing attendance efficiently.
            </p>
            <p class="mb-6 text-lg text-center text-gray-700">Experience seamless attendance tracking with
                AttendanceGuru.</p>
            <p class="mb-6 text-lg text-center text-gray-700">Effortlessly manage attendance records and generate
                reports with ease.</p>
            <p class="mb-6 text-lg text-center text-gray-700">Stay organized and make informed decisions with
                AttendanceGuru.</p>
            <p class="mb-6 text-lg text-center text-gray-700">Join us today and revolutionize your attendance management
                process.</p>
            <p class="mb-6 text-lg text-center text-gray-700">Get started now!</p>

            <a href="{{ route('login') }}" <div class="flex justify-center">
                <a href="/login"
                    class="px-4 py-2 text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">Login</a>
                <a href="/register"
                    class="px-4 py-2 ml-4 text-white transition duration-300 bg-yellow-500 rounded-lg hover:bg-yellow-600">Register</a>
            <p class="mt-8 text-sm text-gray-600">© 2025 AttendanceGuru. All rights reserved DEVELOPMENT BY PT GOLDEN
                NATIONAL LECACY.</p>
        </div>
    </div>
    </div>
</body>

</html>
