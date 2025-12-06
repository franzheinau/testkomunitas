<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard (announcement + cuplikan post karya).
     */
    public function index()
    {
        // ambil 1 announcement terbaru (is_announcement = true)
        $announcement = Post::where('is_announcement', true)
                             ->orderByDesc('published_at')
                             ->orderByDesc('created_at')
                             ->first();

        // ambil 4 post karya terbaru (non-announcement) untuk sekilas
        $latestPosts = Post::where(function ($q) {
                                $q->whereNull('is_announcement')->orWhere('is_announcement', false);
                            })
                            ->latest()
                            ->take(4)
                            ->get();

        return view('dashboard', compact('announcement', 'latestPosts'));
    }
}
