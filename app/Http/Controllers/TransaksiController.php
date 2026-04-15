<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\Kendaraan;
use App\Models\Area;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with(['kendaraan','area','tarif'])
            ->whereIn('status', ['parkir','keluar'])
            ->orderBy('id','desc')
            ->get();

        // 🔥 Ambil semua kendaraan yang SUDAH PERNAH transaksi
        $kendaraanTerpakai = Transaksi::pluck('id_kendaraan');

        // 🔥 Tampilkan hanya yang BELUM PERNAH transaksi
        $kendaraan = Kendaraan::whereNotIn('id', $kendaraanTerpakai)->get();

        $area = Area::all();

        return view('transaksi.index', compact('transaksi','kendaraan','area'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kendaraan' => 'required',
            'id_area' => 'required'
        ]);

        // ✅ Cek apakah kendaraan masih parkir
        $cekParkir = Transaksi::where('id_kendaraan', $request->id_kendaraan)
                        ->whereNull('waktu_keluar')
                        ->exists();

        if ($cekParkir) {
            return back()->with('error', 'Kendaraan masih parkir!');
        }

        $kendaraan = Kendaraan::findOrFail($request->id_kendaraan);
        $area = Area::findOrFail($request->id_area);

        if ($area->terisi >= $area->kapasitas) {
            return back()->with('error', 'Area parkir penuh!');
        }

        Transaksi::create([
            'id_kendaraan' => $request->id_kendaraan,
            'id_tarif'     => $kendaraan->id_tarif,
            'waktu_masuk'  => now(),
            'waktu_keluar' => null,
            'status'       => 'parkir',
            'id_user'      => auth()->id(),
            'id_area'      => $request->id_area
        ]);

        $area->increment('terisi');

        return redirect()->route('transaksi.index')
            ->with('success','Transaksi berhasil ditambahkan');
    }

    public function keluar($id)
    {
        $transaksi = Transaksi::with('tarif')->findOrFail($id);

        if ($transaksi->waktu_keluar != null) {
            return back()->with('error','Sudah diproses');
        }

        $waktuKeluar = now();
        $waktuMasuk  = Carbon::parse($transaksi->waktu_masuk);

        $selisihMenit = $waktuMasuk->diffInMinutes($waktuKeluar);

        $tarifPerJam = $transaksi->tarif->tarif_per_jam;
        $tarifPerMenit = $tarifPerJam / 60;

        $totalBayar = $selisihMenit * $tarifPerMenit;

        $transaksi->update([
            'waktu_keluar' => $waktuKeluar,
            'durasi_jam'   => floor($selisihMenit / 60),
            'durasi_menit' => $selisihMenit % 60,
            'biaya_total'  => round($totalBayar),
            'status'       => 'keluar'
        ]);

        return back()->with('success','Silakan lakukan pembayaran');
    }

    public function bayar($id)
    {
        $transaksi = Transaksi::with(['kendaraan','area'])->findOrFail($id);

        if ($transaksi->status != 'keluar') {
            return back()->with('error','Belum diproses keluar');
        }

        // Data struk
        $data = [
            'plat'   => $transaksi->kendaraan->plat_kendaraan,
            'warna'  => $transaksi->kendaraan->warna,
            'area'   => $transaksi->area->nama_area,
            'masuk'  => $transaksi->waktu_masuk,
            'keluar' => $transaksi->waktu_keluar,
            'total'  => $transaksi->biaya_total,
        ];

        // Kurangi kapasitas
        $transaksi->area->decrement('terisi');

        // ✅ Update jadi selesai (tidak dihapus)
        $transaksi->update([
            'status' => 'selesai'
        ]);

        session(['struk' => $data]);

        return redirect('/struk');
    }
}