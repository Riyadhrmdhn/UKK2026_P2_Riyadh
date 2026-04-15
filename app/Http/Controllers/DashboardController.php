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

    // ================= ADMIN =================
    if ($role == 'admin') {

        $totalUser = User::count();
        $totalKendaraan = Kendaraan::count();

        return view('main.admin', compact(
            'totalUser',
            'totalKendaraan'
        ));
    }

    // ================= PETUGAS =================
    if ($role == 'petugas') {

        $today = now()->toDateString();

        $masukHariIni = Transaksi::whereDate('waktu_masuk', $today)->count();
        $keluarHariIni = Transaksi::whereDate('waktu_keluar', $today)->count();

        return view('main.petugas', compact(
            'masukHariIni',
            'keluarHariIni'
        ));
    }

    // ================= OWNER (🔥 BARU) =================
    if ($role == 'owner') {

        $today = now()->toDateString();

        $masukHariIni = Transaksi::whereDate('waktu_masuk', $today)->count();

        $keluarHariIni = Transaksi::whereDate('waktu_keluar', $today)->count();

        $pendapatanHariIni = Transaksi::whereDate('waktu_keluar', $today)
                                ->sum('biaya_total');

        $parkirAktif = Transaksi::where('status', 'parkir')->count();

        return view('main.owner', compact(
            'masukHariIni',
            'keluarHariIni',
            'pendapatanHariIni',
            'parkirAktif'
        ));
    }

    abort(403);
}

}