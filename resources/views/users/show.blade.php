<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-4">

        @if($user->role)
          <span class="text-sm text-slate-500 px-3 py-1 rounded-full bg-slate-50">{{ $user->role }}</span>
        @endif
      </div>

      @auth
        @if(auth()->id() === $user->id)
          <a href="{{ route('profile.edit') }}"
             class="px-4 py-2 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700 transition">
            Edit Profil
          </a>
        @endif
      @endauth
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-6xl mx-auto px-4">

      {{-- HERO / COVER --}}
      <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-indigo-50 to-white shadow">
        <div class="h-44 md:h-56 bg-[linear-gradient(180deg,#eef2ff_0%,#ffffff_80%)] flex items-end">
          {{-- optional cover image --}}
          @if($user->cover)
            <div class="absolute inset-0">
              <div class="w-full h-full bg-cover bg-center" style="background-image: url({{ asset('storage/'.$user->cover) }}); filter: blur(2px) brightness(.7);"></div>
              <div class="absolute inset-0 bg-gradient-to-b from-transparent to-white/80"></div>
            </div>
          @endif

          <div class="w-full px-4 md:px-8 pb-4 flex items-end justify-between">
            <div class="flex items-end gap-4">
              {{-- Avatar --}}
              <div class="relative -mt-10">
                <div class="w-28 h-28 md:w-36 md:h-36 rounded-full overflow-hidden ring-4 ring-white shadow-lg bg-white">
                  <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('images/default-avatar.png') }}"
                       alt="{{ $user->name }} avatar"
                       class="w-full h-full object-cover">
                </div>
                {{-- small status dot (example) --}}
                @if($user->is_online ?? false)
                  <span class="absolute right-0 bottom-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
                @endif
              </div>

              <div class="pt-1">
                <h1 class="text-xl md:text-2xl font-extrabold text-slate-900">{{ $user->name }}</h1>
                <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ $user->headline ?? ($user->bio ? \Illuminate\Support\Str::limit($user->bio, 80) : 'Belum menambahkan bio — tambahkan bio singkat agar profil tidak kosong.') }}</p>

                <div class="mt-3 flex flex-wrap gap-2 items-center">
                  {{-- Website badge --}}
                  @if($user->website)
                    <a href="{{ $user->website }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:underline bg-indigo-50 px-3 py-1 rounded-full">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                      <span class="max-w-[180px] truncate">{{ \Illuminate\Support\Str::limit($user->website, 30) }}</span>
                    </a>
                  @else
                    <span class="text-xs text-slate-400 px-3 py-1 rounded-full bg-slate-50">Belum ada website</span>
                  @endif

                  {{-- Location --}}
                  @if($user->location)
                    <span class="text-sm text-slate-500 px-3 py-1 rounded-full bg-slate-50">{{ $user->location }}</span>
                  @endif
                </div>
              </div>
            </div>

            {{-- Stats di hero (visible lg) --}}
            <div class="hidden md:flex items-center gap-6">
              <div class="text-center">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- MAIN GRID --}}
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8 items-start">
        {{-- SIDEBAR --}}
        <aside class="lg:col-span-1 bg-white rounded-2xl p-6 shadow-sm flex flex-col gap-4">
          <div class="flex flex-col items-center text-center">
            <h3 class="text-lg font-bold text-slate-900">{{ $user->name }}</h3>
            <p class="text-xs text-slate-500 mt-1">{{ $user->bio ?? 'Belum menambahkan bio.' }}</p>
          </div>

          <div class="grid grid-cols-3 gap-3 text-center mt-2">
            <div class="bg-slate-50 rounded-xl p-3">
              <p class="text-xl font-bold" id="followersCountAlt">{{ $followersCount }}</p>
              <p class="text-xs text-slate-500">Followers</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3">
              <p class="text-xl font-bold" id="followingCountAlt">{{ $followingCount }}</p>
              <p class="text-xs text-slate-500">Following</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3">
              <p class="text-xl font-bold">{{ $posts->total() }}</p>
              <p class="text-xs text-slate-500">Posting</p>
            </div>
          </div>

          {{-- action buttons --}}
          <div class="mt-4 w-full">
            @auth
              @if(auth()->id() !== $user->id)
                <form id="followForm" action="{{ route('users.follow.toggle', $user) }}" method="POST" class="w-full">
                  @csrf
                  <button id="followBtn"
                          class="w-full py-2 rounded-xl text-sm border bg-white hover:bg-slate-50 flex items-center justify-center gap-2 transition"
                          aria-pressed="{{ $isFollowing ? 'true' : 'false' }}">
                    @if($isFollowing)
                      {{-- Following state --}}
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414-1.414L8 11.172 4.707 7.879A1 1 0 003.293 9.293l4 4a1 1 0 001.414 0l8-8z" clip-rule="evenodd"/></svg>
                      <span>Following</span>
                    @else
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                      <span>Follow</span>
                    @endif
                  </button>
                </form>
              @endif
            @endauth

            @auth
              @if(auth()->id() === $user->id)
                <a href="{{ route('posts.create') }}" class="mt-3 block w-full text-center px-3 py-2 rounded-xl bg-indigo-600 text-white">Buat Post</a>
              @endif
            @endauth
          </div>

          {{-- extra info / tags --}}
          <div class="mt-4 text-sm text-slate-600 space-y-2">
            <div>
              <span class="font-medium">Bergabung sejak:</span>
              <div class="mt-2 text-xs text-slate-500">{{ $user->created_at->format('d M Y') }} ({{ $user->created_at->diffForHumans() }})</div>
            </div>

            <div>
              <span class="font-medium">Hubungi:</span>
              <div class="mt-2 text-xs text-slate-500">
                <div>Email: {{ $user->email ?? '-' }}</div>
                <div>Website: {{ $user->website ? \Illuminate\Support\Str::limit($user->website, 30) : '-' }}</div>
              </div>
            </div>
          </div>
        </aside>

        {{-- POSTS AREA --}}
        <main class="lg:col-span-2 space-y-6">

          <div class="flex items-center justify-between">
            <h4 class="text-xl font-bold text-slate-900">Karya oleh {{ $user->name }}</h4>
            <div class="text-sm text-slate-500">{{ $posts->total() }} post</div>
          </div>

          {{-- posts list (card style) --}}
          <div class="grid grid-cols-1 gap-6">
            @forelse($posts as $post)
              <a href="{{ route('posts.show', $post) }}" class="block bg-white rounded-2xl shadow-sm hover:shadow-md transition p-5 group">
                <div class="flex gap-5 items-start">
                  {{-- Thumbnail --}}
                  <div class="w-32 h-24 rounded-xl overflow-hidden flex-shrink-0 bg-slate-100">
                    @if($post->image)
                      <img src="{{ asset('storage/'.$post->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M7 7v10m10-10v10M7 7h10M5 3h14l1 2H4l1-2z" /></svg>
                        </div>
                    @endif
                  </div>

                  {{-- Content --}}
                  <div class="flex-1">
                    <h5 class="font-semibold text-indigo-600 text-lg leading-tight line-clamp-2">{{ $post->title }}</h5>

                    <div class="flex items-center justify-between mt-1">
                      <p class="text-xs text-slate-400">{{ $post->created_at->diffForHumans() }}</p>

                    </div>

                    <p class="mt-3 text-sm text-slate-700 line-clamp-3 leading-relaxed">
                      @if($post->caption)
                        {{ \Illuminate\Support\Str::limit($post->caption, 150) }}
                      @else
                        {{ \Illuminate\Support\Str::limit(strip_tags($post->body), 150) }}
                      @endif
                    </p>

                    {{-- tags / meta --}}
                    <div class="mt-3 flex flex-wrap gap-2">
                      @if($post->tags && count($post->tags))
                        @foreach($post->tags as $tag)
                          <span class="text-xs text-slate-500 bg-slate-50 px-2 py-1 rounded-md">#{{ $tag }}</span>
                        @endforeach
                      @endif
                    </div>
                  </div>
                </div>
              </a>
            @empty
              {{-- nicer empty state --}}
              <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-slate-500">
                <div class="mx-auto max-w-xs">
                  <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M7 7v10m10-10v10M7 7h10M5 3h14l1 2H4l1-2z" />
                  </svg>
                  <h5 class="mt-4 font-semibold text-slate-700">Belum ada postingan</h5>
                  <p class="mt-2 text-sm text-slate-400">Pengguna belum memposting karya apapun. Cobalah kembali nanti.</p>

                  @auth
                    @if(auth()->id() === $user->id)
                      <a href="{{ route('posts.create') }}" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-xl">Buat Post Pertama</a>
                    @endif
                  @endauth
                </div>
              </div>
            @endforelse
          </div>

          {{-- Pagination --}}
          <div>
            {{ $posts->links() }}
          </div>
        </main>
      </div>
    </div>
  </div>

  {{-- FOLLOW AJAX (improved UX: disabled while loading + spinner + error handling) --}}
<script>
  (function () {
    const followForm = document.getElementById('followForm');
    if (!followForm) return;

    followForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      const btn = document.getElementById('followBtn');
      if (!btn) return;

      const originalHTML = btn.innerHTML;
      btn.disabled = true;
      btn.classList.add('opacity-70', 'cursor-wait');
      btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity="0.25"/><path d="M22 12a10 10 0 00-10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>Loading';

      try {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const res = await fetch(this.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (!res.ok) throw new Error('Network response was not ok');

        const data = await res.json();

        // update UI
        btn.disabled = false;
        btn.classList.remove('opacity-70', 'cursor-wait');

        if (data.status === 'followed') {
          btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414-1.414L8 11.172 4.707 7.879A1 1 0 003.293 9.293l4 4a1 1 0 001.414 0l8-8z" clip-rule="evenodd"/></svg><span>Following</span>';
        } else {
          btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg><span>Follow</span>';
        }

        // aman: cek elemen dulu baru di-update
        const fc = document.getElementById('followersCount');
        if (fc) fc.textContent = data.followersCount;

        const fcAlt = document.getElementById('followersCountAlt');
        if (fcAlt) fcAlt.textContent = data.followersCount;

        const fg = document.getElementById('followingCount');
        if (fg) fg.textContent = data.followingCount;

        const fgAlt = document.getElementById('followingCountAlt');
        if (fgAlt) fgAlt.textContent = data.followingCount;

      } catch (err) {
        console.error(err);
        btn.disabled = false;
        btn.classList.remove('opacity-70', 'cursor-wait');
        btn.innerHTML = originalHTML;
        alert('Terjadi error. Coba lagi nanti.');
      }
    });
  })();
</script>
</x-app-layout>
