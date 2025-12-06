<section class="space-y-4">
    <header class="mb-2">
        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900"> {{ __('Hapus Akun') }} </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi yang ingin Anda simpan.') }}
        </p>
    </header>

    <div class="bg-white shadow-lg rounded-2xl p-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-base font-medium text-gray-900">{{ __('Hapus Akun Secara Permanen') }}</h3>
                <p class="mt-1 text-sm text-gray-600"> {{ __('Ini akan menghapus seluruh data Anda. Tindakan ini tidak dapat dibatalkan.') }} </p>
            </div>

            <div class="ms-4">
                <x-danger-button
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    class="px-4 py-2 rounded-lg"
                >
                    {{ __('Hapus Akun') }}
                </x-danger-button>
            </div>
        </div>

        <!-- Modal (tidak diubah fungsi) -->
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Harap masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
                </p>

                <div class="mt-6">
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4 rounded-lg border-gray-200 shadow-sm"
                        placeholder="{{ __('Password') }}"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg">
                        {{ __('Batal') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700">
                        {{ __('Hapus Akun') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>
</section>
