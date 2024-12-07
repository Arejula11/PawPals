<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FollowController;


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
Route::controller(PasswordController::class)->group(function() {
    Route::get('/password-reset', 'index')->name('password');
    Route::post('/password-reset/send', 'send')->name('password.send');
});

// Search Page
Route::controller(SearchController::class)->group(function() {
    Route::get('/search', 'index')->name('search');
});

// Search Users - AJAX Request
Route::get('/search-users', [SearchController::class, 'searchUsers'])->name('search.users');

// Post
Route::controller(PostController::class)->group(function () {
    Route::get('/posts', 'index')->name('posts.index'); // List all posts
    Route::get('/posts/create', 'create')->name('posts.create'); // Create a new post
    Route::post('/posts', 'store')->name('posts.store'); // Store a new post
    Route::get('/posts/{id}', 'show')->name('posts.show'); // Show a specific post
    Route::get('/posts/{id}/edit', 'edit')->name('posts.edit'); // Edit a specific post
    Route::put('/posts/{id}', 'update')->name('posts.update'); // Update a specific post
    Route::delete('/posts/{id}', 'destroy')->name('posts.destroy'); // Delete a specific post
});

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

// Users profile
Route::get('/user/{id}', [UserController::class, 'show'])->name('users.show');
Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
Route::put('/user/edit/{id}', [UserController::class, 'update'])->name('users.update');

//Route::post('/file/upload', [FileController::class, 'upload']);


Route::get('admin/user/{id}', [UserController::class, 'show'])->name('admin.users.show');
Route::get('admin/', [UserController::class, 'admin'])->name('users.admin');
Route::get('admin/user/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
Route::put('admin/user/edit/{id}', [UserController::class, 'update'])->name('admin.users.update');

Route::post('/update-message', [MessageController::class, 'updateMessage']);

Route::post('/follow', [UserController::class, 'follow'])->name('follow.send');
Route::get('/requests', [UserController::class, 'checkRequests'])->name('requests.show');
Route::post('follow/accept/{user1_id}/{user2_id}', [UserController::class, 'accept'])->name('follow.accept');
Route::post('follow/reject/{user1_id}/{user2_id}', [UserController::class, 'reject'])->name('follow.reject');


Route::get('/settings', [UserController::class, 'settings'])->name('settings.show');
Route::put('/settings/changePassword/{id}', [UserController::class, 'updatePassword'])->name('user.updatePassword');
Route::put('/settings/user/delete/{id}', [UserController::class, 'deleteUser'])->name('settings.users.delete');
Route::put('/settings/user/public/{id}', [UserController::class, 'privacity'])->name('settings.users.public');
