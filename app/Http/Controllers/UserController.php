<?php

// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        // ambil paginated posts untuk user
        $posts = $user->posts()
                      ->where(function ($q) {
                          $q->whereNull('is_announcement')->orWhere('is_announcement', false);
                      })
                      ->latest()
                      ->paginate(10);

        // apakah pengunjung sudah mengikuti user ini?
        $isFollowing = auth()->check()
            ? auth()->user()->following()->where('following_id', $user->id)->exists()
            : false;

        // tambahkan counts
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return view('users.show', compact(
            'user','posts','isFollowing','followersCount','followingCount'
        ));
    }
}

