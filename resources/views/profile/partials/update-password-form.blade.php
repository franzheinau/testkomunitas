<section>
    <header class="mb-6">
        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900"> {{ __('Perbarui Kata Sandi') }} </h2>
        <p class="mt-1 text-sm text-gray-600">{{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}</p>
    </header>

    <div class="bg-white shadow-lg rounded-2xl p-6">
        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            @method('put')

            <div>
                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-2 block w-full rounded-lg border-gray-200 shadow-sm" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password" :value="__('New Password')" />
                <x-text-input id="update_password_password" name="password" type="password" class="mt-2 block w-full rounded-lg border-gray-200 shadow-sm" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full rounded-lg border-gray-200 shadow-sm" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">{{ __('Simpan') }}</x-primary-button>

                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>
</section>
