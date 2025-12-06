<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        // pastikan user harus login untuk mengakses resource ini
        $this->middleware('auth');

        // otomatis gunakan policy untuk resource (authorizeResource akan memanggil policy)
        $this->authorizeResource(Post::class, 'post');
    }

    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        // Admin lihat semua (termasuk pengumuman). User biasa TIDAK melihat pengumuman.
        if (Auth::user()->hasAnyRole(['admin', 'super-admin'])) {
            $posts = Post::latest()->paginate(10);
        } else {
            $posts = Post::where(function ($q) {
                        $q->whereNull('is_announcement')->orWhere('is_announcement', false);
                    })
                    ->latest()
                    ->paginate(10);
        }

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(Request $request)
    {
        $this->authorize('create', Post::class);

        // kembalikan flag jika route dipanggil dengan ?type=announcement
        $isAnnouncement = $request->query('type') === 'announcement' && Auth::user()->hasAnyRole(['admin', 'super-admin']);

        return view('posts.create', compact('isAnnouncement'));
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'body'             => 'nullable|string',
            'caption'          => 'nullable|string|max:500',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096', // max 4MB
            'published_at'     => 'nullable|date',
            'is_announcement'  => 'nullable|boolean',
        ]);

        // hanya izinkan admin menandai announcement (jika bukan admin, paksakan false)
        if (! Auth::user()->hasAnyRole(['admin', 'super-admin'])) {
            $data['is_announcement'] = false;
        } else {
            // jika admin, ambil dari input (checkbox / hidden)
            $data['is_announcement'] = (bool) ($request->input('is_announcement') ?? false);
        }

        // handle upload image (simpan di storage/app/public/posts)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post = Auth::user()->posts()->create($data);

        // jika post ini ditandai announcement dan memiliki published_at kosong, set sekarang
        if (! empty($post->is_announcement) && empty($post->published_at)) {
            $post->update(['published_at' => now()]);
        }

        return redirect()->route('posts.show', $post)->with('success', 'Post berhasil dibuat.');
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'body'             => 'nullable|string',
            'caption'          => 'nullable|string|max:500',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'published_at'     => 'nullable|date',
            'is_announcement'  => 'nullable|boolean',
        ]);

        // hanya admin bisa ubah flag announcement
        if (! Auth::user()->hasAnyRole(['admin', 'super-admin'])) {
            unset($data['is_announcement']);
        } else {
            $data['is_announcement'] = (bool) ($request->input('is_announcement') ?? false);
        }

        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->route('posts.show', $post)->with('success', 'Post berhasil diperbarui.');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // hapus file gambar bila ada
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post berhasil dihapus.');
    }



}
