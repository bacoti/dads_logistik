<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\MaterialController;

use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;

use App\Http\Controllers\User\TransactionController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $role = Auth::user()->role->value;
    if ($role == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role == 'user') {
        // Arahkan ke dashboard user yang baru kita buat
        return redirect()->route('user.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Grup untuk Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Rute untuk dashboard admin
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Rute untuk manajemen transaksi oleh admin
        Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');
        Route::patch('/transactions/{transaction}/approve', [AdminTransactionController::class, 'approve'])->name('transactions.approve');
        Route::patch('/transactions/{transaction}/reject', [AdminTransactionController::class, 'reject'])->name('transactions.reject');
    });

    // Grup untuk User Biasa
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        // Rute untuk dashboard user (pemilihan form)
        Route::get('/dashboard', [TransactionController::class, 'dashboard'])->name('dashboard');

        // Rute untuk menampilkan form transaksi berdasarkan jenisnya
        Route::get('/transactions/create/{type}', [TransactionController::class, 'create'])->name('transactions.create');

        // Rute untuk menyimpan data transaksi baru
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

        // Rute untuk riwayat transaksi (akan kita bangun nanti)
        // Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    });

});

require __DIR__.'/auth.php';
