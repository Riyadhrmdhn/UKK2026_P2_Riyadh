<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    public function index()
    {
        $tarif = Tarif::all();
        return view('tarif.index', compact('tarif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required',
            'tarif_per_jam' => 'required|numeric|min:100'
        ]);

        Tarif::create($request->all());

        return back()->with('success', 'Tarif berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_kendaraan' => 'required',
            'tarif_per_jam' => 'required|numeric|min:100'
        ]);

        $tarif = Tarif::findOrFail($id);
        $tarif->update($request->all());

        return back()->with('success', 'Tarif berhasil diupdate');
    }

    public function destroy($id)
    {
        Tarif::findOrFail($id)->delete();

        return back()->with('success', 'Tarif berhasil dihapus');
    }
}