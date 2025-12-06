<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between gap-4">
      <div>
        <h1 class="font-extrabold text-2xl md:text-3xl text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">Ringkasan & berita terbaru dari komunitas</p>
      </div>

      @role('admin|super-admin')
        <a href="{{ route('posts.create') }}?type=announcement"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
           </svg>
           Buat Pengumuman
        </a>
      @endrole
    </div>
  </x-slot>

  {{-- offset supaya tidak tertutup nav fixed --}}
  <div class="mt-16 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      {{-- HERO / COVER ANNOUNCEMENT --}}
      @if($announcement)
        <article class="relative rounded-2xl overflow-hidden shadow-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white">
          <div class="md:flex md:items-stretch">
            {{-- left: image (on md+) --}}
            @if($announcement->image)
              <div class="hidden md:block md:w-1/3">
                <img src="{{ asset('storage/' . $announcement->image) }}" alt="{{ $announcement->title }}" class="h-full object-cover w-full">
              </div>
            @endif

            {{-- right: content --}}
            <div class="p-6 md:p-10 md:flex-1">
              <div class="flex items-start justify-between">
                <div>
                  <div class="text-sm opacity-90">Pengumuman</div>
                  <h2 class="mt-2 text-2xl md:text-4xl font-extrabold leading-tight">{{ $announcement->title }}</h2>
                  <div class="mt-2 text-sm opacity-90">oleh {{ $announcement->user->name ?? 'Admin' }} • {{ $announcement->created_at->format('j M Y, H:i') }}</div>
                </div>

                {{-- small badge --}}
                <div class="ml-4 hidden md:flex items-center">
                  <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-sm">Highlight</span>
                </div>
              </div>

              <p class="mt-5 text-base md:text-lg leading-relaxed text-white/95"
                 style="-webkit-line-clamp:4; display:-webkit-box; -webkit-box-orient:vertical; overflow:hidden;">
                {!! \Illuminate\Support\Str::limit(strip_tags($announcement->caption ?: $announcement->body), 220, '...') !!}
              </p>

              <div class="mt-6 flex flex-wrap items-center gap-3">
                <a href="{{ route('posts.show', $announcement) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-white text-indigo-700 font-semibold shadow-sm">
                  Baca selengkapnya
                </a>
                <span class="text-sm opacity-90">Kategori: {{ $announcement->type ?? 'Pengumuman' }}</span>
              </div>
            </div>
          </div>
        </article>
      @else
        <div class="rounded-2xl border border-dashed border-slate-200 p-6 md:p-8 text-center text-slate-500 bg-white shadow-sm">
          <h3 class="text-lg font-semibold text-slate-800">Belum ada pengumuman</h3>
          <p class="mt-2 text-sm">Admin dapat membuat pengumuman untuk memberi info penting kepada pengguna.</p>
          @role('admin|super-admin')
            <div class="mt-4">
              <a href="{{ route('posts.create') }}?type=announcement" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                + Buat Pengumuman
              </a>
            </div>
          @endrole
        </div>
      @endif

      {{-- 2-column main layout: Left = content feed, Right = sidebar --}}
      <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- LEFT: Main feed (magazine style) --}}
        <main class="lg:col-span-2 space-y-6">

          {{-- HIGHLIGHT ROW: horizontal cards (e.g., Editor's Pick, Upcoming Events, Trending) --}}
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Editor's Pick (first latest post or announcement) --}}
            <div class="rounded-2xl bg-white shadow p-4">
              <h4 class="text-sm font-semibold text-slate-800">Editor's Pick</h4>
              @php $pick = $latestPosts->first(); @endphp
              @if($pick)
                <a href="{{ route('posts.show', $pick) }}" class="block mt-3">
                  @if($pick->image)
                    <div class="w-full h-28 rounded-md overflow-hidden">
                      <img src="{{ asset('storage/' . $pick->image) }}" alt="{{ $pick->title }}" class="w-full h-full object-cover">
                    </div>
                  @endif
                  <div class="mt-3">
                    <div class="text-sm font-semibold text-indigo-600">{{ \Illuminate\Support\Str::limit($pick->title, 60) }}</div>
                    <div class="mt-1 text-xs text-slate-500">{{ $pick->created_at->diffForHumans() }}</div>
                  </div>
                </a>
              @else
                <div class="mt-3 text-sm text-slate-500">Belum ada post untuk pilihan editor.</div>
              @endif
            </div>

            {{-- Upcoming Events (if you have events route, else show small promo) --}}
            <div class="rounded-2xl bg-white shadow p-4">
              <h4 class="text-sm font-semibold text-slate-800">Upcoming</h4>
              <div class="mt-3 text-sm text-slate-600">
                {{-- jika ada variable events, ganti $upcomingEvents --}}
                <p>Tidak ada event mendatang.</p>
                <a href="{{ route('events.create') }}" class="mt-3 inline-flex items-center text-xs text-indigo-600 hover:underline">
                  @can('events.create') Buat Event @else Lihat Event @endcan
                </a>
              </div>
            </div>

            {{-- Trending (jumlah recent posts) --}}
            <div class="rounded-2xl bg-white shadow p-4">
              <h4 class="text-sm font-semibold text-slate-800">Trending</h4>
              <div class="mt-3 space-y-2">
                @foreach($latestPosts->take(3) as $t)
                  <a href="{{ route('posts.show', $t) }}" class="flex items-start gap-3 hover:bg-slate-50 p-2 rounded-md">
                    @if($t->image)
                      <div class="w-16 h-12 rounded-md overflow-hidden flex-shrink-0">
                        <img src="{{ asset('storage/' . $t->image) }}" alt="{{ $t->title }}" class="w-full h-full object-cover">
                      </div>
                    @endif
                    <div class="min-w-0">
                      <div class="text-sm font-semibold text-indigo-600 truncate">{{ \Illuminate\Support\Str::limit($t->title, 60) }}</div>
                      <div class="text-xs text-slate-500 mt-1">{{ $t->created_at->diffForHumans() }}</div>
                    </div>
                  </a>
                @endforeach
              </div>
            </div>
          </div>

          {{-- MAIN ARTICLE LIST (news feed) --}}
          <section class="space-y-4">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold text-slate-900">Berita & Post Terbaru</h3>
              <a href="{{ route('posts.index') }}" class="text-sm text-indigo-600 hover:underline">Lihat semua</a>
            </div>

            <div class="space-y-4">
              @forelse($latestPosts as $post)
                <article class="bg-white rounded-2xl shadow overflow-hidden">
                  <div class="md:flex">
                    @if($post->image)
                      <div class="md:w-1/3">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                      </div>
                    @endif
                    <div class="p-4 md:p-6 flex-1">
                      <div class="flex items-start justify-between">
                        <div>
                          <h4 class="text-lg font-semibold text-slate-900 leading-tight">
                            <a href="{{ route('posts.show', $post) }}" class="hover:underline">{{ \Illuminate\Support\Str::limit($post->title, 120) }}</a>
                          </h4>
                          <div class="mt-1 text-xs text-slate-500">oleh {{ $post->user->name ?? '-' }} • {{ $post->created_at->diffForHumans() }}</div>
                        </div>
                      </div>

                      <p class="mt-3 text-sm text-slate-700"
                         style="-webkit-line-clamp:3; display:-webkit-box; -webkit-box-orient:vertical; overflow:hidden;">
                        {!! \Illuminate\Support\Str::limit(strip_tags($post->caption ?: $post->body), 180, '...') !!}
                      </p>

                      <div class="mt-4 flex items-center gap-3">
                        <a href="{{ route('posts.show', $post) }}" class="text-sm font-semibold text-indigo-600 hover:underline">Baca selengkapnya</a>
                        {{-- you can add tags or category here --}}
                        <span class="text-xs text-slate-400">•</span>

                      </div>
                    </div>
                  </div>
                </article>
              @empty
                <div class="rounded-2xl bg-white p-6 text-center text-slate-500 shadow">
                  Belum ada post karya.
                </div>
              @endforelse
            </div>
          </section>
        </main>

        {{-- RIGHT: Sidebar --}}
        <aside class="space-y-6">
          {{-- Sekilas Post Karya --}}
          <div class="rounded-2xl bg-white p-4 shadow">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-sm font-semibold text-slate-800">Sekilas Post Karya</h4>
              <a href="{{ route('posts.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
            </div>

            <div class="divide-y divide-slate-100">
              @forelse($latestPosts->take(5) as $post)
                <a href="{{ route('posts.show', $post) }}" class="block py-3 hover:bg-slate-50 transition px-1 rounded-lg">
                  <div class="flex gap-3 items-start">
                    @if($post->image)
                      <div class="w-16 h-12 rounded-md overflow-hidden flex-shrink-0">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                      </div>
                    @endif

                    <div class="flex-1 min-w-0">
                      <div class="text-indigo-600 font-semibold text-sm truncate">{{ \Illuminate\Support\Str::limit($post->title, 60) }}</div>
                      <div class="text-xs text-slate-500 mt-1">oleh {{ $post->user->name ?? '-' }} • {{ $post->created_at->diffForHumans() }}</div>
                    </div>
                  </div>
                </a>
              @empty
                <div class="py-4 text-sm text-slate-500">Belum ada post.</div>
              @endforelse
            </div>
          </div>

          {{-- Quick Actions / Shortcuts --}}
          <div class="rounded-2xl bg-white p-4 shadow">
            <h5 class="text-sm font-semibold text-slate-800 mb-3">Quick Actions</h5>
            <div class="flex flex-col gap-3">
              <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-100 text-sm hover:bg-slate-50">Semua Post</a>
              @can('events.create')
                <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 text-white text-sm hover:bg-indigo-700">+ Buat Event</a>
              @endcan
              @role('admin|super-admin')
                <a href="{{ route('posts.create') }}?type=announcement" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-50 text-indigo-600 text-sm hover:bg-indigo-100">+ Buat Pengumuman</a>
              @endrole
              @hasrole('manager|admin')
                <a href="{{ route('teams.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-100 text-sm hover:bg-slate-50">Kelola Tim</a>
              @endhasrole
            </div>
          </div>

          {{-- Tips & Shortcut --}}
          <div class="rounded-2xl bg-white p-4 shadow">
            <h5 class="text-sm font-semibold text-slate-800 mb-2">Tips & Shortcut</h5>
            <ul class="text-sm text-slate-600 space-y-2">
              <li class="flex items-start gap-2"><span class="mt-0.5 text-indigo-600">•</span> Gunakan gambar 1200x800 untuk tampilan terbaik.</li>
              <li class="flex items-start gap-2"><span class="mt-0.5 text-indigo-600">•</span> Gunakan deskripsi singkat & tags agar mudah dicari.</li>
            </ul>
          </div>
        </aside>
      </div>
    </div>
  </div>
</x-app-layout>
