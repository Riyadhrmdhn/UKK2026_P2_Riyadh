<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
public function index()
{
    $jumlahUser = \App\Models\User::count();
    $jumlahKendaraan = \App\Models\Kendaraan::count();

    // 🔥 Masuk terakhir (yang ada waktu masuk)
    $masukTerakhir = \App\Models\Transaksi::with('kendaraan')
        ->whereNotNull('waktu_masuk')
        ->latest('waktu_masuk')
        ->first();

    // 🔥 Keluar terakhir (yang sudah keluar)
    $keluarTerakhir = \App\Models\Transaksi::with('kendaraan')
        ->whereNotNull('waktu_keluar')
        ->latest('waktu_keluar')
        ->first();

    return view('dashboard', compact(
        'jumlahUser',
        'jumlahKendaraan',
        'masukTerakhir',
        'keluarTerakhir'
    ));
}
}