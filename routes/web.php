<?php

use Illuminate\Support\Facades\Route;   
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\AreaController;

// 🔥 Default halaman pertama
Route::get('/', [AuthController::class, 'showLogin']);

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// 🔹 REGISTER
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// 🔹 DASHBOARD (DI LUAR AUTH FOLDER)

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');
// 🔹 USER

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});

Route::prefix('kendaraan')->group(function () {
    Route::get('/', [KendaraanController::class, 'index'])->name('kendaraan.index');
    Route::post('/store', [KendaraanController::class, 'store'])->name('kendaraan.store');
    Route::put('/update/{id}', [KendaraanController::class, 'update'])->name('kendaraan.update');
    Route::delete('/delete/{id}', [KendaraanController::class, 'destroy'])->name('kendaraan.destroy');
});

    Route::get('/tarif', [TarifController::class, 'index'])->name('tarif.index');
    Route::post('/tarif', [TarifController::class, 'store'])->name('tarif.store');
    Route::put('/tarif/{id}', [TarifController::class, 'update'])->name('tarif.update');
    Route::delete('/tarif/{id}', [TarifController::class, 'destroy'])->name('tarif.destroy');  



    Route::get('/area', [AreaController::class, 'index'])->name('area.index');
    Route::post('/area', [AreaController::class, 'store'])->name('area.store');
    Route::put('/area/{id}', [AreaController::class, 'update'])->name('area.update');
    Route::delete('/area/{id}', [AreaController::class, 'destroy'])->name('area.destroy');
// 🔹 LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');