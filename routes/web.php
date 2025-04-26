<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvatarController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/aboutus', function () {
    return view('aboutus');
})->name('aboutus');

Route::get('/avatar/{userId}', [ProfileController::class, 'getAvatar'])->name('avatar');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/homepage', function () {
    // Menggunakan paginate() untuk mengambil 12 produk per halaman
    $products = \App\Models\Product::paginate(24);

    // Mengirimkan data produk ke view
    return view('homepage', compact('products'));
})->middleware(['auth', 'verified'])->name('homepage');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-image', [ProfileController::class, 'updateImage'])->name('profile.updateImage');
});

Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin', [ProductController::class, 'index'])->name('admin');
});


Route::middleware(['auth',IsAdmin::class])->group(function () {
    Route::resource('products', ProductController::class);
});


Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

require __DIR__ . '/auth.php';
