<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\HomePageController;

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

Route::get('/homepage', [HomePageController::class, 'index'])->middleware(['auth', 'verified'])->name('homepage');
Route::post('/products/{product}/purchase', [HomePageController::class, 'purchase'])
    ->middleware('auth') // Pastikan hanya user terautentikasi yang bisa membeli
    ->name('products.purchase');
Route::get('/products/{product}/options', [HomePageController::class, 'options']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-image', [ProfileController::class, 'updateImage'])->name('profile.updateImage');
});

Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin', [ProductController::class, 'index'])->name('admin');
});


Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::resource('products', ProductController::class);
});
//api
Route::get('/api/products', [ProductController::class, 'apiGetProduct']);

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

require __DIR__ . '/auth.php';
