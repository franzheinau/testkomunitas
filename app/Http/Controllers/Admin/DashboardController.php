<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super-admin|admin']);
    }

    public function index()
    {
        // statistik sederhana
        $usersCount = User::count();
        $postsCount = Post::count();
        $rolesCount = Role::count();
        $permissionsCount = Permission::count();

        // ambil 6 post terbaru untuk admin preview
        $latestPosts = Post::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'usersCount','postsCount','rolesCount','permissionsCount','latestPosts'
        ));
    }
}
