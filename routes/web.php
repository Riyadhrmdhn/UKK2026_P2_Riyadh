<?php

use Illuminate\Support\Facades\Route;   
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\TransaksiController;

// 🔥 Default halaman pertama
Route::get('/', [AuthController::class, 'showLogin']);

// 🔹 LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// 🔹 REGISTER
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


// ================================
// 🔐 SEMUA ROUTE WAJIB LOGIN
// ================================
Route::middleware(['auth'])->group(function () {

    // 🔹 DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // 🔹 LOG AKTIVITAS
    Route::get('/log', [LogAktivitasController::class, 'index'])
        ->name('log.index');

    // 🔹 USER
    Route::prefix('people')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('people.index');
        Route::get('/create', [UserController::class, 'create'])->name('people.create');
        Route::post('/store', [UserController::class, 'store'])->name('people.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('people.edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('people.update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('people.destroy');
    });

    // 🔹 KENDARAAN
    Route::prefix('kendaraan')->group(function () {
        Route::get('/', [KendaraanController::class, 'index'])->name('kendaraan.index');
        Route::post('/store', [KendaraanController::class, 'store'])->name('kendaraan.store');
        Route::put('/update/{id}', [KendaraanController::class, 'update'])->name('kendaraan.update');
        Route::delete('/delete/{id}', [KendaraanController::class, 'destroy'])->name('kendaraan.destroy');
    });

    // 🔹 TARIF
    Route::get('/tarif', [TarifController::class, 'index'])->name('tarif.index');
    Route::post('/tarif', [TarifController::class, 'store'])->name('tarif.store');
    Route::put('/tarif/{id}', [TarifController::class, 'update'])->name('tarif.update');
    Route::delete('/tarif/{id}', [TarifController::class, 'destroy'])->name('tarif.destroy');

    // 🔹 AREA
    Route::get('/area', [AreaController::class, 'index'])->name('area.index');
    Route::post('/area', [AreaController::class, 'store'])->name('area.store');
    Route::put('/area/{id}', [AreaController::class, 'update'])->name('area.update');
    Route::delete('/area/{id}', [AreaController::class, 'destroy'])->name('area.destroy');


    Route::middleware(['auth'])->group(function () {
        // Halaman utama transaksi
        Route::get('/transaksi', 
        [TransaksiController::class, 'index']
        )->name('transaksi.index');
        // Simpan kendaraan masuk
        Route::post('/transaksi', 
        [TransaksiController::class, 'store']
        )->name('transaksi.store');
        // Proses kendaraan keluar
        Route::get('/transaksi/keluar/{id}', 
        [TransaksiController::class, 'keluar']
        )->name('transaksi.keluar');
        // Hapus transaksi
        Route::get('transaksi/{id}/bayar', [TransaksiController::class, 'bayar'])
        ->name('transaksi.bayar');
    });

    // 🔹 LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});