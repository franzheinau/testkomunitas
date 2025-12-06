<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user)
    {
        return true; // semua user bisa melihat daftar (sesuaikan)
    }

    public function view(User $user, Post $post)
    {
        return true; // publik untuk sekarang
    }

    public function create(User $user)
    {
        return $user !== null; // hanya user terautentikasi
    }

    public function update(User $user, Post $post)
    {
        // boleh update kalau pemilik atau punya role admin / super-admin
        return $user->id === $post->user_id || $user->hasAnyRole(['admin', 'super-admin']);
    }

    public function delete(User $user, Post $post)
    {
        // hanya pemilik atau admin
        return $user->id === $post->user_id || $user->hasAnyRole(['admin', 'super-admin']);
    }

    public function restore(User $user, Post $post)
    {
        return $user->hasAnyRole(['admin', 'super-admin']);
    }

    public function forceDelete(User $user, Post $post)
    {
        return $user->hasAnyRole(['admin', 'super-admin']);
    }
}
