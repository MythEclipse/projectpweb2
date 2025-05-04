<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\AdminController; // Pastikan namespace benar
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Middleware\IsAdmin; // Pastikan middleware di-import4
use App\Http\Controllers\Admin\UserController; // Import UserController untuk admin
use App\Http\Controllers\WishlistController; // Import WishlistController untuk wishlist

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// == Public Routes ==
Route::get('/', function () {
    return view('welcome');
})->name('welcome'); // Beri nama jika belum
Route::post('/session/clear-flash', function () {
    session()->forget(['success', 'error']);
    return response()->json(['status' => 'cleared']);
})->name('session.clear.flash');
Route::get('/aboutus', function () {
    return view('aboutus');
})->name('aboutus');

// Avatar Route (Diasumsikan public)
Route::get('/avatar/{userId}', [ProfileController::class, 'getAvatar'])->name('avatar');

// Google OAuth Routes
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

// == Authenticated User Routes ==
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        // Pertimbangkan untuk redirect ke 'homepage' jika itu dashboard utama Anda
        return view('dashboard');
    })->name('dashboard');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle'); // Pakai POST karena mengubah data
    // Homepage dan interaksi produk user
    Route::get('/homepage', [HomePageController::class, 'index'])->name('homepage');
    Route::post('/products/{product}/purchase', [HomePageController::class, 'purchase'])->name('products.purchase');
    Route::get('/products/{product}/options', [HomePageController::class, 'options'])->name('products.options'); // Beri nama jika belum

    // Profile Routes (Bisa dipisah ke grup 'auth' saja jika verifikasi email tidak wajib untuk edit profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-image', [ProfileController::class, 'updateImage'])->name('profile.updateImage');
});


Route::middleware(['auth', 'verified', IsAdmin::class]) // Terapkan semua middleware di grup
    ->prefix('admin') // Tambahkan prefix URL '/admin'
    ->name('admin.') // Tambahkan prefix nama route 'admin.'
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('index'); // Nama route menjadi 'admin.index'

        // Kelola Produk (Resource Controller)
        Route::resource('products', ProductController::class)->except([
            // Exclude any methods if you don't need them, e.g., 'create', 'edit' if handled differently
        ]);

        // If you need the API route (it was in your original controller)
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::resource('users', UserController::class);
        // Kelola Transaksi (Resource Controller)
        Route::resource('transactions', TransactionController::class); // URL: /admin/transactions, Nama: admin.transactions.*

        // ===> ADD THIS LINE <===
        // Route custom untuk quick update transaksi (harus didefinisikan terpisah dari resource)
        Route::patch('transactions/{transaction}/quick-update', [TransactionController::class, 'quickUpdate'])
             ->name('transactions.quick_update'); // Nama route menjadi 'admin.transactions.quick_update'

        // Tambahkan route admin lainnya di sini jika ada...
});

// == API Routes (Sebaiknya di routes/api.php) ==
// Route ini akan menggunakan middleware group 'web' jika diletakkan di sini.
// Pindahkan ke routes/api.php untuk menggunakan middleware 'api' (stateless, dll).
Route::get('/api/products', [ProductController::class, 'apiGetProduct'])
      ->name('api.products.index'); // Beri nama untuk API


// == Authentication Routes (dari Laravel Breeze/UI) ==
require __DIR__ . '/auth.php';
