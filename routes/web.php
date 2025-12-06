<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController; // main dashboard

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// gunakan controller untuk dashboard (auth middleware)
Route::get('/dashboard',[DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth', 'role:super-admin|admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

Route::group(['middleware' => ['auth']], function () {
    // halaman create event (form)
    Route::get('/events/create', [EventController::class, 'create'])
        ->name('events.create')
        ->middleware('permission:events.create');

    // contoh store route (opsional - buat proses submit)
    Route::post('/events', [EventController::class, 'store'])
        ->name('events.store')
        ->middleware('permission:events.create');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('posts', PostController::class);
});

Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class,'store'])->name('posts.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class,'destroy'])->name('comments.destroy');

    Route::post('/posts/{post}/like', [LikeController::class,'toggle'])->name('posts.like.toggle');

});

Route::post('/users/{user}/follow', [App\Http\Controllers\FollowController::class, 'toggle'])
    ->name('users.follow.toggle')
    ->middleware('auth');

Route::view('/terms', 'terms')->name('terms');

require __DIR__.'/auth.php';
