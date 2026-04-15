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
use App\Http\Controllers\RekapController;

// ================================
// 🔓 PUBLIC (TANPA LOGIN)
// ================================

// Default
Route::get('/', [AuthController::class, 'showLogin']);

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


// ================================
// 🔐 WAJIB LOGIN
// ================================
Route::middleware(['auth'])->group(function () {

    // ================= DASHBOARD =================
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ================= LOG =================
    Route::get('/log', [LogAktivitasController::class, 'index'])
        ->name('log.index');

    // ================= USER =================
    Route::prefix('people')->name('people.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // ================= KENDARAAN =================
    Route::prefix('kendaraan')->name('kendaraan.')->group(function () {
        Route::get('/', [KendaraanController::class, 'index'])->name('index');
        Route::post('/store', [KendaraanController::class, 'store'])->name('store');
        Route::put('/update/{id}', [KendaraanController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [KendaraanController::class, 'destroy'])->name('destroy');
    });

    // ================= TARIF =================
    Route::prefix('tarif')->name('tarif.')->group(function () {
        Route::get('/', [TarifController::class, 'index'])->name('index');
        Route::post('/store', [TarifController::class, 'store'])->name('store');
        Route::put('/update/{id}', [TarifController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [TarifController::class, 'destroy'])->name('destroy');
    });

    // ================= AREA =================
    Route::prefix('area')->name('area.')->group(function () {
        Route::get('/', [AreaController::class, 'index'])->name('index');
        Route::post('/store', [AreaController::class, 'store'])->name('store');
        Route::put('/update/{id}', [AreaController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AreaController::class, 'destroy'])->name('destroy');
    });

    // ================= TRANSAKSI =================
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('index');
        Route::post('/store', [TransaksiController::class, 'store'])->name('store');
        Route::get('/keluar/{id}', [TransaksiController::class, 'keluar'])->name('keluar');
        Route::get('/{id}/bayar', [TransaksiController::class, 'bayar'])->name('bayar');
    });

    // ================= REKAP =================    
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
    Route::get('/struk', function () {
    $data = session('struk');
    if (!$data) {
        return redirect('/transaksi');
    }
    return view('transaksi.struk', compact('data'));
});
    // ================= LOGOUT =================
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});