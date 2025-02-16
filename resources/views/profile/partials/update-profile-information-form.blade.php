<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <!-- Form untuk mengirim verifikasi email -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Form untuk memperbarui profil -->
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Nama -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="block w-full mt-1" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="block w-full mt-1" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- ID Guru -->
        <div>
            <x-input-label for="id_guru" :value="__('ID Guru')" />
            <x-text-input id="id_guru" name="id_guru" type="text" class="block w-full mt-1" :value="old('id_guru', $user->id_guru)" required autocomplete="id_guru" />
            <x-input-error class="mt-2" :messages="$errors->get('id_guru')" />
        </div>

        <!-- Program Studi -->
        <div>
            <x-input-label for="program_studi" :value="__('Program Studi')" />
            <x-text-input id="program_studi" name="program_studi" type="text" class="block w-full mt-1" :value="old('program_studi', $user->program_studi)" required autocomplete="program_studi" />
            <x-input-error class="mt-2" :messages="$errors->get('program_studi')" />
        </div>

        <!-- Alamat -->
        <div>
            <x-input-label for="alamat" :value="__('Alamat')" />
            <x-text-input id="alamat" name="alamat" type="text" class="block w-full mt-1" :value="old('alamat', $user->alamat)" required autocomplete="alamat" />
            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
        </div>

        <!-- No WhatsApp -->
        <div>
            <x-input-label for="no_whatsapp" :value="__('No WhatsApp')" />
            <x-text-input id="no_whatsapp" name="no_whatsapp" type="text" class="block w-full mt-1" :value="old('no_whatsapp', $user->no_whatsapp)" required autocomplete="no_whatsapp" />
            <x-input-error class="mt-2" :messages="$errors->get('no_whatsapp')" />
        </div>

        <!-- Tombol Simpan -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>