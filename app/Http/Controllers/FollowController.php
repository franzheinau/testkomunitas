<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

  // app/Http/Controllers/FollowController.php
public function toggle(User $user)
{
    $me = auth()->user();
    if ($me->id === $user->id) {
        return response()->json(['error' => 'Cannot follow yourself.'], 400);
    }

    $exists = $me->following()->where('following_id', $user->id)->exists();

    if ($exists) {
        $me->following()->detach($user->id);
        $status = 'unfollowed';
    } else {
        $me->following()->attach($user->id);
        $status = 'followed';
    }

    return response()->json([
        'status' => $status,
        'followersCount' => $user->followers()->count(),
        'followingCount' => $user->following()->count(),
    ]);
}
}

