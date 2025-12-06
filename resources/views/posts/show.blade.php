<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="font-extrabold text-2xl text-slate-900">{{ $post->title }}</h2>
        <p class="mt-1 text-sm text-slate-500">oleh <a href="{{ route('users.show', $post->user) }}" class="text-indigo-600 hover:underline">{{ $post->user->name }}</a> • {{ $post->created_at->format('j M Y, H:i') }}</p>
      </div>

      <div class="flex items-center gap-3">
        <button id="likeBtn-{{ $post->id }}" data-post="{{ $post->id }}" class="like-btn inline-flex items-center gap-2 px-3 py-1 rounded-lg border border-slate-100 hover:bg-slate-50 transition">
          <span class="like-emoji text-base">{{ auth()->user() && auth()->user()->likedPosts->contains($post->id) ? '👍🏻' : '👍🏿' }}</span>
          <span class="likes-count text-sm text-slate-600">{{ $post->likes()->count() }}</span>
        </button>

        <button id="shareBtn" data-url="{{ request()->fullUrl() }}" class="inline-flex items-center gap-2 px-3 py-1 rounded-lg border border-slate-100 hover:bg-slate-50 transition">
          Share
        </button>

        @can('update', $post)
          <a href="{{ route('posts.edit', $post) }}" class="px-3 py-1 rounded-lg border text-sm">Edit</a>
        @endcan
        @can('delete', $post)
          <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus post ini?');" class="inline">
            @csrf @method('DELETE')
            <button type="submit" class="px-3 py-1 rounded-lg bg-red-600 text-white text-sm">Hapus</button>
          </form>
        @endcan
      </div>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

      {{-- hero image --}}
      @if($post->image)
        <div class="rounded-2xl overflow-hidden shadow-lg mb-6">
          <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover">
          @if($post->caption)
            <div class="p-3 bg-white/80 text-sm text-slate-600">
              {{ $post->caption }}
            </div>
          @endif
        </div>
      @endif

      {{-- article card --}}
      <div class="bg-white rounded-2xl shadow p-6">
        <div class="prose max-w-none text-slate-800">
          {!! nl2br(e($post->body)) !!}
        </div>

        {{-- tags / meta (optional) --}}
        <div class="mt-6 flex items-center justify-between flex-wrap gap-3">
          <div class="flex items-center gap-2">
            @if(isset($post->tags) && count($post->tags))
              @foreach($post->tags as $tag)
                <span class="text-xs px-2 py-1 rounded-full border text-slate-600">{{ $tag }}</span>
              @endforeach
            @endif
          </div>

          <div class="text-sm text-slate-500">Kategori: {{ $post->type ?? 'Umum' }}</div>
        </div>

        {{-- author card --}}
        <div class="mt-8 border-t pt-6">
          <div class="flex items-start gap-4">
            <a href="{{ route('users.show', $post->user) }}" class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0">
              <img src="{{ $post->user->avatar ? asset('storage/'.$post->user->avatar) : asset('images/default-avatar.png') }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
            </a>

            <div class="flex-1">
              <a href="{{ route('users.show', $post->user) }}" class="text-lg font-semibold text-slate-900 hover:underline">{{ $post->user->name }}</a>
              @if($post->user->website)
                <div class="text-sm text-indigo-600">
                  <a href="{{ $post->user->website }}" target="_blank" rel="nofollow noreferrer">{{ \Illuminate\Support\Str::limit($post->user->website, 60) }}</a>
                </div>
              @endif

              @if($post->user->bio)
                <p class="mt-2 text-sm text-slate-700">{{ $post->user->bio }}</p>
              @else
                <p class="mt-2 text-sm text-slate-400">Pengguna belum menambahkan bio.</p>
              @endif
            </div>
          </div>
        </div>

        {{-- comment form --}}
        <div class="mt-6">
          <form id="commentForm" action="{{ route('posts.comments.store', $post) }}" method="POST">
            @csrf
            <label for="body" class="sr-only">Tulis komentar</label>
            <textarea name="body" rows="3" id="body" class="w-full rounded-lg border border-slate-100 p-3 text-sm" placeholder="Tulis komentar..."></textarea>
            <div class="mt-3 flex items-center justify-between">
              <div class="text-sm text-slate-400">Ingat untuk tetap sopan di komentar.</div>
              <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Kirim</button>
            </div>
          </form>

          <ul id="commentsList" class="mt-4 space-y-3">
            @foreach($post->comments as $comment)
              <li class="p-3 bg-slate-50 rounded-lg">
                <div class="flex items-start justify-between">
                  <div>
                    <div class="text-sm font-medium text-slate-800">{{ $comment->user->name }}</div>
                    <div class="text-xs text-slate-500">{{ $comment->created_at->diffForHumans() }}</div>
                  </div>
                  @can('delete', $comment)
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                      @csrf @method('DELETE')
                      <button class="text-red-600 text-sm">Hapus</button>
                    </form>
                  @endcan
                </div>
                <div class="mt-2 text-sm text-slate-700">{{ $comment->body }}</div>
              </li>
            @endforeach
          </ul>
        </div>

        <div class="mt-6">
          <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:underline">
            ← Kembali ke semua post
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- JS: like + share --}}
  <script>
    // like button
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
          btn.querySelector('.like-emoji').textContent = (json.status === 'liked') ? '👍🏿' : '👍🏻';
          btn.querySelector('.likes-count').textContent = json.likesCount;
        }catch(err){
          console.error(err);
        }
      });
    });

    // share
    document.getElementById('shareBtn')?.addEventListener('click', async () => {
      const url = document.getElementById('shareBtn').dataset.url || window.location.href;
      try{
        if(navigator.share){
          await navigator.share({ title: document.title, url });
        } else if(navigator.clipboard){
          await navigator.clipboard.writeText(url);
          alert('Link disalin ke clipboard!');
        } else {
          prompt('Salin link ini:', url);
        }
      }catch(e){ console.error(e); }
    });
  </script>
</x-app-layout>
