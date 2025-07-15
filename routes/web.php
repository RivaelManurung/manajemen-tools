<?php

use Illuminate\Support\Facades\Route;

// Import semua controller yang dibutuhkan
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowController ; // Controller khusus Mekanik

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == HALAMAN UTAMA & AUTENTIKASI ==

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// == ADMIN PANEL (Untuk Admin & Storeman) ==
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Peralatan (Tools)
    Route::resource('peralatan', PeralatanController::class);

    // CRUD User
    Route::resource('users', UserController::class);
    
    // Peminjaman & Pengembalian
    Route::get('borrow', [BorrowController::class, 'index'])->name('borrow.index');
    Route::post('borrow', [BorrowController::class, 'storeBorrow'])->name('borrow.store');
    Route::post('return', [BorrowController::class, 'storeReturn'])->name('return.store');
    
    // Log Peminjaman
    Route::get('logs', [BorrowController::class, 'logs'])->name('logs.index');

});


// == USER PANEL (Untuk Mekanik) ==
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {

    // Halaman untuk mekanik melihat alat yang sedang ia pinjam
    Route::get('/borrow', [BorrowController::class, 'index'])->name('borrow.index');
    
    // Anda bisa menambahkan route lain untuk mekanik di sini nanti
});