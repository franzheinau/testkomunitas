{{-- resources/views/terms.blade.php --}}
<x-public-layout>
  <div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-8">

      {{-- Logosss  --}}
      <aside class="lg:col-span-3 flex flex-col items-center lg:items-start text-center lg:text-left">
        <div class="w-full lg:sticky lg:top-24">
          <img src="https://ik.imagekit.io/3fgrgeqi6z/pthree.png?updatedAt=1764753926583" alt="Pthree Logo"
               class="mx-auto lg:mx-0 w-20 h-20 lg:w-24 lg:h-24 object-contain mb-4">

          <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
            Terms &amp; Conditions
          </h1>

          <p class="mt-3 text-sm text-slate-500 max-w-xs">
            Halaman ini menjelaskan syarat penggunaan platform komunitas Pthree. Silakan baca sebelum menggunakan situs.
          </p>

          <a href="{{ url('/') }}"
             class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700 transition">
            ← Kembali
          </a>
        </div>
      </aside>

      {{-- MAIN: Terms content --}}
      <main class="lg:col-span-6">
        <div class="bg-white rounded-2xl shadow p-6 sm:p-8 text-slate-700 leading-relaxed">
          {{-- meta header --}}
          <div class="flex items-start justify-between">
            <div>
              <p class="text-sm text-slate-500">Terakhir diperbarui: {{ date('d F Y') }}</p>
              <h2 class="mt-2 text-lg sm:text-2xl font-semibold text-slate-900">Syarat &amp; Ketentuan Penggunaan</h2>
            </div>
          </div>

          <hr class="my-6 border-slate-100">

          {{-- 1 --}}
          <section id="usage" class="mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-2">1. Penggunaan Website</h3>
            <p class="text-sm text-slate-600 mb-2">
              Website ini disediakan sebagai wadah berbagi informasi, karya, dan kegiatan komunitas. Pengguna wajib menggunakan layanan secara bertanggung jawab dan mematuhi norma serta aturan yang berlaku.
            </p>
          </section>

          {{-- 2 --}}
          <section id="account" class="mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-2">2. Akun Pengguna</h3>
            <p class="text-sm text-slate-600 mb-2">
              Beberapa fitur mengharuskan pengguna membuat akun. Pengguna bertanggung jawab menjaga kerahasiaan informasi akun dan bertanggung jawab atas seluruh aktivitas yang dilakukan melalui akunnya.
            </p>
            <p class="text-sm text-slate-600">
              Jika ada indikasi penyalahgunaan, laporkan segera ke admin untuk tindak lanjut.
            </p>
          </section>

          {{-- 3 --}}
          <section id="forbidden" class="mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-2">3. Konten yang Dilarang</h3>
            <ul class="list-disc pl-5 space-y-2 text-sm text-slate-600">
              <li>Konten SARA, ujaran kebencian, ancaman atau kekerasan.</li>
              <li>Konten pornografi atau materi yang melanggar norma sekolah.</li>
              <li>Karya yang melanggar hak cipta atau plagiarisme.</li>
              <li>Penipuan, hoaks, serta konten yang berbahaya (malware, phising).</li>
            </ul>
          </section>

          {{-- 4 --}}
          <section id="removal" class="mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-2">4. Penghapusan &amp; Moderasi</h3>
            <p class="text-sm text-slate-600">
              Admin berhak menghapus, menyembunyikan, atau menonaktifkan konten dan akun yang melanggar ketentuan tanpa pemberitahuan terlebih dahulu.
            </p>
          </section>

          {{-- 5 --}}
          <section id="ip" class="mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-2">5. Kekayaan Intelektual</h3>
            <p class="text-sm text-slate-600 mb-2">
              Semua materi original di platform ini merupakan milik Pthree atau kontributor yang bersangkutan. Pastikan setiap unggahan tidak melanggar hak cipta pihak ketiga.
            </p>
          </section>

          {{-- 6 --}}
          <section id="updates" class="mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-2">6. Perubahan Ketentuan</h3>
            <p class="text-sm text-slate-600">
              Kami dapat memperbarui syarat &amp; ketentuan ini kapan saja. Perubahan akan berlaku segera setelah dipublikasikan; disarankan untuk memeriksa halaman ini secara berkala.
            </p>
          </section>

          {{-- 7 --}}
          <section id="privacy" class="mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-2">7. Privasi &amp; Data</h3>
            <p class="text-sm text-slate-600">
              Kebijakan privasi yang terpisah menjelaskan bagaimana kami mengumpulkan dan menggunakan data. Pthree berkomitmen menjaga keamanan data pengguna sesuai regulasi yang berlaku.
            </p>
          </section>

          {{-- contact --}}
          <section id="contact" class="pt-2 border-t border-slate-100">
            <h3 class="text-sm font-semibold text-slate-900 mb-2">Kontak</h3>
            <p class="text-sm text-slate-600">
              Untuk pertanyaan atau pelaporan, hubungi admin melalui email: <a href="mailto:admin@pthree.test" class="text-indigo-600 hover:underline">admin@pthree.test</a>
            </p>
          </section>
        </div>

        <div class="mt-6 text-xs text-slate-400 text-center lg:text-left">
          © {{ date('Y') }} Pthree. All rights reserved.
        </div>
      </main>

      {{-- RIGHT: Table of contents (only on large screens) --}}
      <aside class="hidden lg:block lg:col-span-3">
        <div class="sticky top-24">
          <div class="bg-white rounded-xl shadow p-4">
            <h4 class="text-sm font-semibold text-slate-900 mb-2">Daftar Isi</h4>
            <nav class="text-sm text-slate-600 space-y-2">
              <a href="#usage" class="block hover:text-indigo-600">1. Penggunaan Website</a>
              <a href="#account" class="block hover:text-indigo-600">2. Akun Pengguna</a>
              <a href="#forbidden" class="block hover:text-indigo-600">3. Konten yang Dilarang</a>
              <a href="#removal" class="block hover:text-indigo-600">4. Penghapusan & Moderasi</a>
              <a href="#ip" class="block hover:text-indigo-600">5. Kekayaan Intelektual</a>
              <a href="#updates" class="block hover:text-indigo-600">6. Perubahan Ketentuan</a>
              <a href="#privacy" class="block hover:text-indigo-600">7. Privasi & Data</a>
              <a href="#contact" class="block hover:text-indigo-600">Kontak</a>
            </nav>
          </div>

          <div class="mt-4 text-xs text-slate-400">
            Versi ringkas: <br>
            - Publik • Bebas akses
          </div>
        </div>
      </aside>

    </div>
  </div>
</x-public-layout>
