<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\MaterialController;

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
        // Nanti kita arahkan ke dashboard user
        return view('dashboard'); // Untuk sementara biarkan ke sini
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

        // Resourceful route untuk Projects
        Route::resource('projects', ProjectController::class);
        Route::resource('vendors', VendorController::class);
        Route::resource('locations', LocationController::class);
        Route::resource('materials', MaterialController::class);
    });

    // Grup untuk User Biasa
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        // Rute untuk form input material akan ada di sini
    });
});

require __DIR__.'/auth.php';
