<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $totalUser = User::count();
        $totalKendaraan = Kendaraan::count();

        $masukHariIni = Transaksi::whereDate('waktu_masuk', $today)->count();
        $keluarHariIni = Transaksi::whereDate('waktu_keluar', $today)->count();

        return view('main.admin', compact(
            'totalUser',
            'totalKendaraan',
            'masukHariIni',
            'keluarHariIni'
        ));
    }
}