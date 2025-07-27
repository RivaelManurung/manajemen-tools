<?php

use Illuminate\Support\Facades\Route;

// Import semua controller yang dibutuhkan
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\StoremanController;

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

    //Transaksi
    Route::resource('transaksi', TransaksiController::class);

    // CRUD User
    Route::resource('users', UserController::class);

    // Job Titles
    Route::resource('job-titles', JobTitleController::class);

    // Departemen
    Route::resource('departments', DepartmentController::class);

    // Storeman
    Route::resource('storeman', StoremanController::class);
});


// == USER PANEL (Untuk Mekanik) ==
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {

    //Peminjaman
    // Route::resource('peminjaman', PeminjamanController::class);
    // peminjaman.index (GET), peminjaman.create (GET), peminjaman.store (POST)
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'create', 'store']);

    // Route untuk pengembalian dibuat terpisah
    Route::post('kembalikan', [PeminjamanController::class, 'kembalikan'])->name('kembalikan');
});
