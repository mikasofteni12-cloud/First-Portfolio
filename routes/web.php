<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ProfileController;
use App\Http\controllers\UserController;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// all users how to register before login 
Route::group(['middleware' => 'auth'], function(){
Route::get('/', [HomeController::class, 'index'])->name('index');
});

// Owner Only
Route::middleware(['auth', 'is_owner'])->group(function (){
    Route::get('/owner/dashboard', function() {
        return 'Welcome to the Owner Dashboard!';
    })->name('owner.dashboard');
});

// post 
Route::middleware(['auth'])->group(function (){
    // home
    Route::get('/', [HomeController::class, 'index'])->name('home');
    // index
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    // create new post
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    // to store
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    // show details
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    // show edit screen
    Route::get('post/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    // to store / update edit
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    // delete
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Bookmark
    Route::post('/post/{post}/bookmark', [BookmarkController::class, 'store'])->name('posts.bookmark');
});

// Profile
Route::middleware(['auth'])->group(function (){
    // show profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    // edit
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    //  Update
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // delete
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::patch('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');




