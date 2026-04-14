<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\Kendaraan;
use App\Models\Area;
use App\Models\Tarif;

class TransaksiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TAMPIL DATA
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $transaksi = Transaksi::with([
            'kendaraan',
            'area',
            'tarif'
        ])
        ->whereIn('status', ['parkir','keluar']) // 🔥 INI YANG PENTING
        ->orderBy('id','desc')
        ->get();

        $kendaraan = Kendaraan::all();
        $area      = Area::all();

        return view('transaksi.index', compact(
            'transaksi',
            'kendaraan',
            'area'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | KENDARAAN MASUK
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'id_kendaraan' => 'required',
            'id_area' => 'required'
        ]);

        // 🔎 Cek kendaraan masih parkir atau tidak
        $cekParkir = Transaksi::where('id_kendaraan', $request->id_kendaraan)
                        ->where('status', 'parkir')
                        ->exists();

        if ($cekParkir) {
            return back()->with('error', 'Kendaraan masih parkir!');
        }

        $kendaraan = Kendaraan::findOrFail($request->id_kendaraan);
        $area = Area::findOrFail($request->id_area);

        // 🔥 Cek kapasitas penuh
        if ($area->terisi >= $area->kapasitas) {
            return back()->with('error', 'Area parkir penuh!');
        }

        // ✅ Tambah transaksi
        Transaksi::create([
            'id_kendaraan' => $request->id_kendaraan,
            'id_tarif'     => $kendaraan->id_tarif,
            'waktu_masuk'  => now(),
            'status'       => 'parkir',
            'id_user'      => auth()->id(),
            'id_area'      => $request->id_area
        ]);

        // 🔥 Tambah terisi
        $area->increment('terisi');

        return redirect()->route('transaksi.index')
                ->with('success','Transaksi berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | KENDARAAN KELUAR
    |--------------------------------------------------------------------------
    */
    public function keluar($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status != 'parkir') {
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
            'status'       => 'keluar' // 🔥 BELUM SELESAI
        ]);

        return back()->with('success','Silakan lakukan pembayaran');
    }
    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */
    public function bayar($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status == 'Selesai') {
            return back()->with('error','Sudah dibayar');
        }

        // 🔥 Tambah kapasitas kosong (kurangi terisi)
        $transaksi->area->decrement('terisi');

        // 🔥 Simpan dulu id kendaraan sebelum transaksi dihapus
        $idKendaraan = $transaksi->id_kendaraan;

        // 🔥 Hapus transaksi
        $transaksi->delete();

        // 🔥 Hapus kendaraan
        Kendaraan::where('id', $idKendaraan)->delete();

        return redirect()->route('transaksi.index')
                ->with('success','Pembayaran berhasil, data dihapus');
    }

}