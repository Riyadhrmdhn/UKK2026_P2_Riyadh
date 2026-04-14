<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Transaksi;

class DashboardController extends Controller
{

public function index()
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $role = auth()->user()->role;

    if ($role == 'admin') {

        $totalUser = User::count();
        $totalKendaraan = Kendaraan::count();

        return view('main.admin', compact(
            'totalUser',
            'totalKendaraan'
        ));
    }

    if ($role == 'petugas') {

        $today = now()->toDateString();

        $masukHariIni = Transaksi::whereDate('waktu_masuk', $today)->count();
        $keluarHariIni = Transaksi::whereDate('waktu_keluar', $today)->count();

        return view('main.petugas', compact(
            'masukHariIni',
            'keluarHariIni'
        ));
    }

    abort(403);
}
}