<nav x-data="{ open: false }"
    class="fixed top-0 left-0 z-50 w-full bg-blue-600 border-b border-blue-700 shadow-md dark:bg-blue-800 dark:border-blue-700">

    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex h-16">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo150.png') }}" alt="Logo" class="w-auto h-12" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-gray-200">
                        {{ __('List Absen') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('transport.index')" :active="request()->routeIs('transport.index')" class="text-white hover:text-gray-200">
                        {{ __('Tunjangan Transport') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('reward')" :active="request()->routeIs('reward')" class="text-white hover:text-gray-200">
                        {{ __('List Transport Guru') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" class="text-white hover:text-gray-200">
                        {{ __('Users') }}
                    </x-nav-link>
                </div>
                <!-- <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('shift-code.index')" :active="request()->routeIs('shift-code.index')" class="text-white hover:text-gray-200">
                        {{ __('Shift') }}
                    </x-nav-link>
                </div> -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('jadwal_guru.index')" :active="request()->routeIs('jadwal_guru.index')" class="text-white hover:text-gray-200">
                        {{ __('Jadwal Shift Guru') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('attendance-location.index')" :active="request()->routeIs('attendance-location.index')" class="text-white hover:text-gray-200">
                        {{ __('Tikor Absensi') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('attendanceview')" :active="request()->routeIs('attendanceview')" class="text-white
                        hover:text-gray-200">
                        {{ __('Absen Harian') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('attendancePkl')" :active="request()->routeIs('attendanceview')" class="text-white
                        hover:text-gray-200">
                        {{ __('Absen PKL') }}
                    </x-nav-link>
                </div>


               
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-white transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md dark:text-gray-400 dark:bg-blue-800 hover:text-gray-200 dark:hover:text-gray-300 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-gray-800 dark:text-gray-200">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                class="text-gray-800 dark:text-gray-200">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 text-white transition duration-150 ease-in-out rounded-md dark:text-gray-500 hover:text-gray-200 dark:hover:text-gray-400 hover:bg-blue-700 dark:hover:bg-blue-900 focus:outline-none focus:bg-blue-700 dark:focus:bg-blue-900 focus:text-gray-200 dark:focus:text-gray-400">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-gray-500">
                {{ __('Absence') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('transport.index')" :active="request()->routeIs('transport.index')" class="text-white hover:text-gray-500">
                {{ __('Transport Allowance') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('reward')" :active="request()->routeIs('reward')" class="text-white hover:text-gray-500">
                {{ __('Transport Reward') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" class="text-white hover:text-gray-500">
                {{ __('Users') }}
            </x-responsive-nav-link>
        </div>

        <!-- <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('shift-code.index')" :active="request()->routeIs('shift-code.index')" class="text-white hover:text-gray-500">
                {{ __('Shift') }}
            </x-responsive-nav-link>
        </div> -->
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('shift-schedules.index')" :active="request()->routeIs('jadwal_guru.index')" class="text-white hover:text-gray-500">
                {{ __('Jadwal Shift Guru') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('attendance-location.index')" :active="request()->routeIs('attendance-location.index')" class="text-white hover:text-gray-500">
                {{ __('Tikor Absensi') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('attendanceview')" :active="request()->routeIs('attendanceview')" class="text-white hover:text-gray-500">
                {{ __('Absen Harian') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('attendancePkl')" :active="request()->routeIs('attendancePkl')" class="text-white hover:text-gray-500">
                {{ __('Absen PKL') }}
            </x-responsive-nav-link>
        </div>


        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-blue-700 dark:border-blue-600">
            <div class="px-4">
                <div class="text-base font-medium text-white dark:text-gray-200">{{ Auth::user()->name }}
                </div>
                <div class="text-sm font-medium text-gray-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:text-gray-500">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();"
                        class="text-white hover:text-gray-500">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
