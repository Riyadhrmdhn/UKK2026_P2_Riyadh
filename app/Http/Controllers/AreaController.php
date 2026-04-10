<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $area = Area::all();
        return view('area.index', compact('area'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_area' => 'required',
            'kapasitas' => 'required|numeric|min:1'
        ]);

        Area::create([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
            'terisi' => 0 // 🔥 default awal kosong
        ]);

        return back()->with('success', 'Area berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_area' => 'required',
            'kapasitas' => 'required|numeric|min:1',
            'terisi' => 'required|numeric'
        ]);

        $area = Area::findOrFail($id);
        $area->update($request->all());

        return back()->with('success', 'Area berhasil diupdate');
    }

    public function destroy($id)
    {
        Area::findOrFail($id)->delete();

        return back()->with('success', 'Area berhasil dihapus');
    }
}