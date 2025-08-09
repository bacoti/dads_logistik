<?php

use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\TransactionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rute halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Rute dashboard utama yang akan mengarahkan berdasarkan peran (role)
Route::get('/dashboard', function () {
    $role = Auth::user()->role->value;
    if ($role == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role == 'user') {
        return redirect()->route('user.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Grup rute yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Rute untuk manajemen profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =====================================================
    // GRUP RUTE UNTUK ADMIN
    // =====================================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Rute untuk dashboard admin
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Resourceful routes untuk semua Master Data
        Route::resource('projects', ProjectController::class);
        Route::resource('vendors', VendorController::class);
        Route::resource('locations', LocationController::class);
        Route::resource('materials', MaterialController::class);

        // Rute untuk manajemen transaksi oleh admin
        Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');
        Route::patch('/transactions/{transaction}/approve', [AdminTransactionController::class, 'approve'])->name('transactions.approve');
        Route::patch('/transactions/{transaction}/reject', [AdminTransactionController::class, 'reject'])->name('transactions.reject');
    });

    // =====================================================
    // GRUP RUTE UNTUK USER BIASA
    // =====================================================
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        // Rute untuk dashboard user (pemilihan form)
        Route::get('/dashboard', [TransactionController::class, 'dashboard'])->name('dashboard');

        // Rute untuk menampilkan form transaksi berdasarkan jenisnya
        Route::get('/transactions/create/{type}', [TransactionController::class, 'create'])->name('transactions.create');

        // Rute untuk menyimpan data transaksi baru
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    });
});

// Memuat rute-rute autentikasi bawaan Laravel Breeze
require __DIR__.'/auth.php';
