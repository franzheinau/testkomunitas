<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="font-extrabold text-2xl text-slate-900">Post Karya</h2>
        <p class="mt-1 text-sm text-slate-500">Karya terbaru dari komunitas — jelajahi dan beri dukungan.</p>
      </div>

      <div class="flex items-center gap-3">
        {{-- UI-only filter chips (non-functional) --}}
        <div class="hidden sm:flex items-center gap-2">
          <button class="px-3 py-1 text-xs rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100">Semua</button>
        </div>

        <a href="{{ route('posts.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
           </svg>
           Buat Post
        </a>
      </div>
    </div>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      {{-- Info row --}}
      <div class="flex items-center justify-between mb-6">
        <div class="text-sm text-slate-500">Menampilkan <span class="font-medium text-slate-700">{{ $posts->total() }}</span> post</div>
        <div class="text-xs text-slate-400">Filter & sort tersedia di versi lanjutan</div>
      </div>

      {{-- Grid of posts --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($posts as $post)
          <article class="bg-white rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden">
            <div class="md:flex">
              {{-- Thumbnail (on md: left; on mobile: top) --}}
              @if($post->image)
                <a href="{{ route('posts.show', $post) }}" class="block md:w-48 md:flex-shrink-0">
                  <div class="h-48 md:h-full w-full overflow-hidden bg-gray-100">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transform hover:scale-105 transition duration-300">
                  </div>
                </a>
              @endif

              <div class="p-4 md:p-5 flex-1 flex flex-col">
                <div class="flex items-start justify-between gap-3">
                  <a href="{{ route('posts.show', $post) }}" class="text-indigo-600 font-semibold text-lg line-clamp-2 hover:underline">
                    {{ \Illuminate\Support\Str::limit($post->title, 80) }}
                  </a>

                  {{-- action group (edit/delete) --}}
                  <div class="flex items-center gap-2">
                    @can('update', $post)
                      <a href="{{ route('posts.edit', $post) }}" class="text-xs px-2 py-1 rounded-md border border-slate-100 text-indigo-600 hover:bg-indigo-50">Edit</a>
                    @endcan
                    @can('delete', $post)
                      <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus post ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs px-2 py-1 rounded-md bg-red-600 text-white hover:bg-red-700">Hapus</button>
                      </form>
                    @endcan
                  </div>
                </div>

                <div class="flex items-center gap-3 mt-3">
                  <a href="{{ route('users.show', $post->user) }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full overflow-hidden bg-gray-100">
                      <img src="{{ $post->user->avatar ? asset('storage/'.$post->user->avatar) : asset('images/default-avatar.png') }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="text-xs text-slate-500">
                      <div class="text-xs text-slate-500">oleh <span class="text-slate-700 font-medium">{{ $post->user->name }}</span></div>
                      <div class="text-xs text-slate-400">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                  </a>
                </div>

                {{-- excerpt --}}
                <div class="mt-3 text-sm text-slate-700 flex-1">
                  @if($post->caption)
                    <p class="line-clamp-3">{!! \Illuminate\Support\Str::limit($post->caption, 200) !!}</p>
                  @else
                    <p class="line-clamp-3">{!! \Illuminate\Support\Str::limit(strip_tags($post->body), 200) !!}</p>
                  @endif
                </div>

                {{-- interactions --}}
                <div class="mt-4 flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <button id="likeBtn-{{ $post->id }}" data-post="{{ $post->id }}" class="like-btn inline-flex items-center gap-2 px-3 py-1 rounded-lg border border-slate-100 hover:bg-slate-50 transition">
                      <span class="like-emoji text-base">{{ auth()->user() && auth()->user()->likedPosts->contains($post->id) ? '👍🏻' : '👍🏿' }}</span>
                      <span class="likes-count text-sm text-slate-600">{{ $post->likes()->count() }}</span>
                    </button>

                    <button data-url="{{ route('posts.show', $post) }}" class="share-btn inline-flex items-center gap-2 px-3 py-1 rounded-lg border border-slate-100 hover:bg-slate-50 transition text-sm">
                      Share
                    </button>
                  </div>

                  <a href="{{ route('posts.show', $post) }}" class="text-sm text-indigo-600 font-semibold hover:underline">Baca selengkapnya →</a>
                </div>
              </div>
            </div>
          </article>
        @endforeach
      </div>

      {{-- Pagination --}}
      <div class="mt-6 flex justify-center">
        {{ $posts->links() }}
      </div>
    </div>
  </div>

  {{-- JS: like + share --}}
  <script>
    document.querySelectorAll('.like-btn').forEach(btn=>{
      btn.addEventListener('click', async (e)=>{
        e.preventDefault();
        const postId = btn.dataset.post;
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        if(!tokenMeta) return alert('CSRF token not found.');
        const token = tokenMeta.getAttribute('content');
        try{
          const res = await fetch(`/posts/${postId}/like`, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': token, 'Accept': 'application/json'},
          });
          const json = await res.json();
          btn.querySelector('.like-emoji').textContent = (json.status === 'liked') ? '👍🏻' : '👍🏿';
          btn.querySelector('.likes-count').textContent = json.likesCount;
        }catch(err){
          console.error(err);
        }
      });
    });

    document.querySelectorAll('.share-btn').forEach(btn=>{
      btn.addEventListener('click', async ()=>{
        const url = btn.dataset.url || window.location.href;
        try{
          if(navigator.share){
            await navigator.share({ title: document.title, url });
          } else if(navigator.clipboard){
            await navigator.clipboard.writeText(url);
            alert('Link disalin ke clipboard!');
          } else {
            prompt('Salin link ini:', url);
          }
        }catch(e){
          console.error(e);
        }
      });
    });
  </script>
</x-app-layout>
