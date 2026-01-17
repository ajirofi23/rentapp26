<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ToyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:owner')->prefix('owner')->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
        Route::get('/performa', [OwnerController::class, 'performa'])->name('owner.performa');
        Route::get('/riwayat', [OwnerController::class, 'riwayat'])->name('owner.riwayat');

        // Toys Management
        Route::get('/toys', [OwnerController::class, 'toys'])->name('owner.toys.index');
        Route::post('/toys', [ToyController::class, 'store'])->name('owner.toys.store');
        Route::put('/toys/{toy}', [ToyController::class, 'update'])->name('owner.toys.update');

        // Karyawan Management
        Route::get('/karyawan', [OwnerController::class, 'karyawan'])->name('owner.karyawan.index');
        Route::post('/karyawan', [OwnerController::class, 'storeKaryawan'])->name('owner.karyawan.store');
        Route::delete('/karyawan/{user}', [OwnerController::class, 'destroyKaryawan'])->name('owner.karyawan.destroy');
    });

    Route::middleware('role:karyawan')->prefix('karyawan')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'karyawan'])->name('karyawan.dashboard');
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::post('/transaksi/{transaksi}/complete', [TransaksiController::class, 'complete'])->name('transaksi.complete');
    });
});
