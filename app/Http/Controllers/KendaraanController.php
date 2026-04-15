<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Tarif;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        /*
        Tampilkan hanya kendaraan yang BELUM PERNAH punya transaksi
        Jadi:
        - Belum transaksi -> tampil
        - Sudah parkir -> hilang
        - Sudah bayar -> tetap hilang
        */

        $kendaraan = Kendaraan::whereDoesntHave('transaksi')
            ->with('tarif')
            ->get();

        $tarif = Tarif::all();

        return view('kendaraan.index', compact('kendaraan', 'tarif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat_kendaraan' => 'required|unique:t_kendaraan,plat_kendaraan',
            'warna' => 'required',
            'id_tarif' => 'required'
        ]);

        Kendaraan::create([
            'plat_kendaraan' => $request->plat_kendaraan,
            'warna' => $request->warna,
            'id_tarif' => $request->id_tarif,
            'id_user' => auth()->id() ?? 1,
        ]);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'plat_kendaraan' => 'required',
            'warna' => 'required',
            'id_tarif' => 'required'
        ]);

        $kendaraan = Kendaraan::findOrFail($id);

        $kendaraan->update([
            'plat_kendaraan' => $request->plat_kendaraan,
            'warna' => $request->warna,
            'id_tarif' => $request->id_tarif
        ]);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil diupdate');
    }

    public function destroy($id)
    {
        Kendaraan::findOrFail($id)->delete();

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil dihapus');
    }
}   