<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == HALAMAN UTAMA & AUTENTIKASI ==

// Arahkan halaman root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route untuk tamu (yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    // Anda bisa menambahkan route register di sini jika diperlukan
});

// Route untuk logout, hanya bisa diakses setelah login
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// == ADMIN PANEL ==
// Semua route di bawah ini hanya bisa diakses setelah login
// dan memiliki awalan URL /admin dan nama route admin.

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Peralatan (Tools)
    // Ini akan secara otomatis membuat route:
    // admin.peralatan.index, admin.peralatan.store, admin.peralatan.update, dll.
    Route::resource('peralatan', PeralatanController::class);

    // CRUD User
    Route::resource('users', UserController::class);
    
    // Peminjaman & Pengembalian
    Route::get('borrow', [BorrowController::class, 'index'])->name('borrow.index');
    Route::post('borrow', [BorrowController::class, 'storeBorrow'])->name('borrow.store');
    
    // Log Peminjaman
    Route::get('logs', [BorrowController::class, 'logs'])->name('logs.index');
    
    // Anda perlu route terpisah untuk pengembalian karena formnya ada di halaman log/peminjaman
    // atau bisa digabung logikanya. Namun untuk kemudahan, kita buat terpisah.
    Route::post('return', [BorrowController::class, 'storeReturn'])->name('return.store');

});