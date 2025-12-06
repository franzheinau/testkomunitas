<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-bold text-2xl text-gray-900">Edit Post</h2>
      <a href="{{ route('posts.show', $post) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border text-sm">Kembali</a>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-3xl mx-auto bg-white shadow rounded-2xl p-6">
      @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
          <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
          <label class="block text-sm font-medium text-gray-700">Judul</label>
          <input type="text" name="title" value="{{ old('title', $post->title) }}" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm" required>
        </div>

        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700">Caption (singkat, opsional)</label>
          <input type="text" name="caption" value="{{ old('caption', $post->caption) }}" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm" placeholder="Contoh: Foto kegiatan minggu ini">
        </div>

        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700">Foto (opsional — unggah untuk mengganti)</label>
          <input type="file" name="image" accept="image/*" class="mt-1 block w-full" id="imageInputEdit">
          <p class="text-xs text-slate-400 mt-1">Format: jpg, png, webp, gif. Maks 4MB.</p>
          @error('image')
            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
          @enderror

          <!-- current image -->
          @if($post->image)
            <div class="mt-4">
              <div class="text-sm text-gray-600 mb-2">Gambar saat ini:</div>
              <img id="currentImg" src="{{ asset('storage/'.$post->image) }}" alt="Current" class="w-full max-w-md rounded-2xl shadow-lg">
            </div>
          @endif

          <!-- preview new -->
          <div id="previewEdit" class="mt-4 hidden">
            <div class="text-sm text-gray-600 mb-2">Preview gambar baru:</div>
            <img id="previewImgEdit" src="" alt="Preview" class="w-full max-w-md rounded-2xl shadow-lg">
          </div>
        </div>

        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700">Konten / Keterangan Panjang (opsional)</label>
          <textarea name="body" rows="8" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm">{{ old('body', $post->body) }}</textarea>
        </div>

        <div class="mt-4 flex gap-3">
          <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white">Update</button>
          <a href="{{ route('posts.show', $post) }}" class="px-4 py-2 rounded-lg border">Batal</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('imageInputEdit')?.addEventListener('change', function (e) {
      const file = e.target.files[0];
      const preview = document.getElementById('previewEdit');
      const previewImg = document.getElementById('previewImgEdit');

      if (!file) {
        preview.classList.add('hidden');
        previewImg.src = '';
        return;
      }

      const url = URL.createObjectURL(file);
      previewImg.src = url;
      preview.classList.remove('hidden');
    });
  </script>
</x-app-layout>
