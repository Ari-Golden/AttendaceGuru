<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block w-full mt-1"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block w-full mt-1"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- ID Guru -->
        <div class="mt-4">
            <x-input-label for="id_guru" :value="__('ID Guru')" />
            <x-text-input id="id_guru" class="block w-full mt-1" type="text" name="id_guru" :value="old('id_guru')" required />
            <x-input-error :messages="$errors->get('id_guru')" class="mt-2" />
        </div>

        <!-- Program Studi -->
        <div class="mt-4">
            <x-input-label for="program_studi" :value="__('Program Studi')" />
            <x-text-input id="program_studi" class="block w-full mt-1" type="text" name="program_studi" :value="old('program_studi')" required />
            <x-input-error :messages="$errors->get('program_studi')" class="mt-2" />
        </div>

        <!-- Alamat -->
        <div class="mt-4">
            <x-input-label for="alamat" :value="__('Alamat')" />
            <x-text-input id="alamat" class="block w-full mt-1" type="text" name="alamat" :value="old('alamat')" required />
            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
        </div>

        <!-- No WhatsApp -->
        <div class="mt-4">
            <x-input-label for="no_whatsapp" :value="__('No WhatsApp')" />
            <x-text-input id="no_whatsapp" class="block w-full mt-1" type="text" name="no_whatsapp" :value="old('no_whatsapp')" required />
            <x-input-error :messages="$errors->get('no_whatsapp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>