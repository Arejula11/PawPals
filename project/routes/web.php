<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/home');
Route::controller(HomeController::class)->group(function() {
    Route::get('/home', 'index')->name('home');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Search Page
Route::controller(SearchController::class)->group(function() {
    Route::get('/search', 'index')->name('search');
});

// Search Users - AJAX Request
Route::get('/search-users', [SearchController::class, 'searchUsers'])->name('search.users');

// Post
Route::get('/posts', [PostController::class, 'create'])->name('posts.create');

Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

Route::controller(GroupController::class)->group(function () {
    Route::get('/groups/search', 'search')->name('groups.search'); // Search for groups
    Route::get('/groups', 'index')->name('groups.index'); // List of groups
    Route::get('/groups/create', 'create')->name('groups.create'); // Form to create a group
    Route::post('/groups', 'store')->name('groups.store'); // Save new group
    Route::get('/groups/{id}/edit', [GroupController::class, 'edit'])->name('groups.edit'); // Edit group form
    Route::put('/groups/{id}', [GroupController::class, 'update'])->name('groups.update'); // Update group
    Route::get('/groups/{id}/messages', 'messages')->name('groups.messages'); // Group messages only
    Route::post('/groups/{id}/join', 'join')->name('groups.join'); // Join group
    Route::get('/groups/{id}', 'show')->name('groups.show'); // Group details
    Route::post('/groups/{id}/messages/store', [GroupController::class, 'storeMessage'])->name('groups.messages.store');
});

