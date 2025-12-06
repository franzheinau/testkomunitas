<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-bold text-2xl text-gray-900">Admin Dashboard</h2>
      <a href="{{ route('posts.create') }}" class="px-3 py-2 rounded-lg bg-indigo-600 text-white">Buat Post</a>
    </div>
  </x-slot>

  <div class="py-8">
    <div class="max-w-7xl mx-auto space-y-8">

      {{-- Summary cards --}}
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow p-4">
          <div class="text-sm text-slate-500">Users</div>
          <div class="mt-2 text-2xl font-bold">{{ $usersCount }}</div>
        </div>
        <div class="bg-white rounded-2xl shadow p-4">
          <div class="text-sm text-slate-500">Posts</div>
          <div class="mt-2 text-2xl font-bold">{{ $postsCount }}</div>
        </div>
        <div class="bg-white rounded-2xl shadow p-4">
          <div class="text-sm text-slate-500">Roles</div>
          <div class="mt-2 text-2xl font-bold">{{ $rolesCount }}</div>
        </div>
        <div class="bg-white rounded-2xl shadow p-4">
          <div class="text-sm text-slate-500">Permissions</div>
          <div class="mt-2 text-2xl font-bold">{{ $permissionsCount }}</div>
        </div>
      </div>

      {{-- Latest posts --}}
      <section>
        <div class="mb-4 flex items-center justify-between">
          <h3 class="text-lg font-semibold">Latest Posts</h3>
          <a href="{{ route('posts.index') }}" class="text-sm text-indigo-600">Manage posts</a>
        </div>

        <div class="space-y-3">
          @forelse($latestPosts as $post)
            <div class="bg-white rounded-2xl shadow p-4 flex items-start gap-4">
              @if($post->image)
                <div class="w-20 h-14 rounded overflow-hidden flex-shrink-0">
                  <img src="{{ asset('storage/' . $post->image) }}" alt="" class="w-full h-full object-cover">
                </div>
              @endif

              <div class="flex-1">
                <div class="flex items-center justify-between">
                  <div>
                    <div class="font-semibold text-indigo-600">{{ $post->title }}</div>
                    <div class="text-xs text-slate-500">oleh {{ $post->user->name ?? '-' }} • {{ $post->created_at->diffForHumans() }}</div>
                  </div>

                  <div class="flex items-center gap-2">
                    <a href="{{ route('posts.edit', $post) }}" class="px-2 py-1 rounded border text-sm">Edit</a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Yakin?');">
                      @csrf @method('DELETE')
                      <button type="submit" class="px-2 py-1 rounded bg-red-600 text-white text-sm">Hapus</button>
                    </form>
                  </div>
                </div>

                <div class="mt-2 text-sm text-slate-700">{!! \Illuminate\Support\Str::limit(strip_tags($post->caption ?: $post->body), 140) !!}</div>
              </div>
            </div>
          @empty
            <div class="text-slate-500">Belum ada post.</div>
          @endforelse
        </div>
      </section>

    </div>
  </div>
</x-app-layout>
