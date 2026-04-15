<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['kendaraan','area','tarif'])
                    ->whereNotNull('waktu_keluar');

        // FILTER TANGGAL
        if ($request->dari && $request->sampai) {
            $query->whereBetween('waktu_keluar', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59'
            ]);
        }

        $rekap = $query->orderBy('id','desc')->get();

        return view('rekap.index', compact('rekap'));
    }
}