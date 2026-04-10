<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Tarif;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraan = Kendaraan::with('tarif')->get();
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

        $tarif = Tarif::findOrFail($request->id_tarif);

        Kendaraan::create([
            'plat_kendaraan' => $request->plat_kendaraan,
            'warna' => $request->warna,
            'status' => null, // 🔥 FIX DI SINI
            'id_user' => auth()->id() ?? 1,
            'id_tarif' => $tarif->id,
            'created_at' => now()
        ]);

        return back()->with('success', 'Kendaraan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'plat_kendaraan' => 'required',
            'warna' => 'required',
            'id_tarif' => 'required'
        ]);

        $kendaraan = Kendaraan::findOrFail($id);
        $tarif = Tarif::findOrFail($request->id_tarif);

        $kendaraan->update([
            'plat_kendaraan' => $request->plat_kendaraan,
            'warna' => $request->warna,
            'id_tarif' => $tarif->id
        ]);

        return back()->with('success', 'Kendaraan berhasil diupdate');
    }

    public function destroy($id)
    {
        Kendaraan::findOrFail($id)->delete();

        return back()->with('success', 'Kendaraan berhasil dihapus');
    }
}