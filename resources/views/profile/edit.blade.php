{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<x-app-layout>
<section>
  <header>
    <h2 class="text-lg font-medium text-gray-900">Informasi Profil</h2>

    <p class="mt-1 text-sm text-gray-600">
      Update akun anda dan informasi email. Tambahkan foto profil, bio, dan tautan website jika ingin.
    </p>
  </header>

  <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
    @csrf
    @method('patch')

    {{-- avatar & preview --}}
    <div class="flex items-start gap-6">
      <div class="flex-shrink-0">
        <div class="w-28 h-28 rounded-full overflow-hidden border bg-gray-100">
          <img id="avatarPreview" src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('images/default-avatar.png') }}" alt="avatar" class="w-full h-full object-cover">
        </div>
      </div>

      <div class="flex-1">
        <label class="block text-sm font-medium text-gray-700">Foto Profil</label>

        <div class="mt-2 flex items-center gap-3">
          <div>
            <input id="avatar" name="avatar" type="file" accept="image/*" class="text-sm" aria-describedby="avatar-help" />
            <div id="avatarFilename" class="text-xs text-slate-500 mt-1">
              {{-- akan diisi oleh JS jika ada file --}}
              @if($user->avatar)
                Saat ini: <span class="font-medium">{{ basename($user->avatar) }}</span>
              @else
                Belum ada avatar.
              @endif
            </div>
            <p id="avatar-help" class="text-xs text-gray-400 mt-1">Format: jpg, png, webp, gif. Maks 4MB.</p>
            <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
          </div>

          <div class="flex items-center gap-2">
            {{-- Tombol hapus avatar (client-side toggle) --}}
            @if($user->avatar)
              <button type="button" id="removeAvatarBtn" class="px-3 py-1 rounded-md bg-red-600 text-white text-sm hover:bg-red-700">
                Hapus Avatar
              </button>
            @endif

            {{-- Tombol reset preview (kembali ke avatar awal) --}}
            <button type="button" id="resetAvatarBtn" class="px-3 py-1 rounded-md border text-sm">
              Batal Pilih
            </button>
          </div>
        </div>

        {{-- hidden flag untuk menghapus avatar di server --}}
        <input type="hidden" name="remove_avatar" id="remove_avatar" value="0" />
      </div>
    </div>

    {{-- name --}}
    <div>
      <x-input-label for="name" :value="__('Nama')" />
      <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
      <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    {{-- email --}}
    <div>
      <x-input-label for="email" :value="__('Email')" />
      <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    {{-- bio --}}
    <div>
      <x-input-label for="bio" :value="__('Bio / Tentang Singkat')" />
      <textarea id="bio" name="bio" rows="4" maxlength="1000" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm">{{ old('bio', $user->bio) }}</textarea>
      <div class="flex items-center justify-between mt-1 text-xs">
        <x-input-error :messages="$errors->get('bio')" class="mt-0" />
        <div id="bioCount" class="text-slate-400">0 / 1000</div>
      </div>
    </div>

    {{-- website --}}
    <div>
      <x-input-label for="website" :value="__('Website / Tautan (opsional)')" />
      <x-text-input id="website" name="website" type="url" class="mt-1 block w-full" :value="old('website', $user->website)" placeholder="https://example.com" />
      <x-input-error :messages="$errors->get('website')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4">
      <x-primary-button>{{ __('Simpan') }}</x-primary-button>

      @if (session('status') === 'profile-updated')
        <p
          x-data="{ show: true }"
          x-show="show"
          x-transition
          x-init="setTimeout(() => show = false, 2000)"
          class="text-sm text-gray-600"
        >{{ __('Tersimpan.') }}</p>
      @endif
    </div>
  </form>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const input = document.getElementById('avatar');
      const img = document.getElementById('avatarPreview');
      const filenameEl = document.getElementById('avatarFilename');
      const removeBtn = document.getElementById('removeAvatarBtn');
      const resetBtn = document.getElementById('resetAvatarBtn');
      const removeFlag = document.getElementById('remove_avatar');

      // simpan src awal supaya bisa di-reset
      const originalSrc = img.src;

      // set awal bio counter
      const bio = document.getElementById('bio');
      const bioCount = document.getElementById('bioCount');
      if (bio && bioCount) {
        const updateBioCount = () => {
          const len = bio.value.length;
          bioCount.textContent = len + " / " + (bio.getAttribute('maxlength') || '1000');
        };
        updateBioCount();
        bio.addEventListener('input', updateBioCount);
      }

      if (!input) return;

      input.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) {
          filenameEl.innerHTML = '{{ $user->avatar ? "Saat ini: " . basename($user->avatar) : "Belum ada avatar." }}';
          return;
        }

        // clear remove flag (user memilih file baru -> bukan menghapus)
        if (removeFlag) removeFlag.value = "0";

        const url = URL.createObjectURL(file);
        img.src = url;
        filenameEl.innerHTML = 'File dipilih: <span class="font-medium">' + file.name + '</span>';
      });

      // tombol reset preview (kembali ke awal; tidak hapus file di server)
      resetBtn?.addEventListener('click', function () {
        input.value = '';
        img.src = originalSrc;
        filenameEl.innerHTML = '{{ $user->avatar ? "Saat ini: " . basename($user->avatar) : "Belum ada avatar." }}';
        if (removeFlag) removeFlag.value = "0";
      });

      // tombol hapus avatar: set flag remove_avatar=1, update preview ke default,
      // clear file input so upload won't overwrite
      removeBtn?.addEventListener('click', function () {
        if (!confirm('Hapus avatar Anda? Tindakan ini akan menghapus avatar dari profil Anda.')) return;
        input.value = '';
        img.src = '{{ asset("images/default-avatar.png") }}';
        filenameEl.innerHTML = 'Avatar akan dihapus saat menyimpan.';
        if (removeFlag) removeFlag.value = "1";
      });
    });
  </script>
</section>
</x-app-layout>
