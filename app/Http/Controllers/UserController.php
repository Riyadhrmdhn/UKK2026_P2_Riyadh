<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogAktivitas; // 🔥 WAJIB TAMBAH
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByRaw("FIELD(role,'admin','petugas','owner')")->get();

        // SHIFT LOGIC
        $petugas = $users->where('role', 'petugas')->values();
        $now = now()->format('H');

        if ($now >= 0 && $now < 8) {
            $shiftAktif = 0;
        } elseif ($now >= 8 && $now < 16) {
            $shiftAktif = 1;
        } else {
            $shiftAktif = 2;
        }

        foreach ($petugas as $i => $p) {
            $p->shift_status = ($i % 3 == $shiftAktif) ? 'aktif' : 'off';
        }

        return view('people.index', compact('users'));
    }

    // ================== TAMBAH USER ==================
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required',
            'status'   => 'required'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status
        ]);

        // 🔥 SIMPAN LOG
        LogAktivitas::create([
            'id_user' => auth()->id(),
            'aktivitas' => 'Menambahkan user: ' . $user->name,
            'waktu_aktivitas' => now()
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    // ================== UPDATE USER ==================
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => 'required',
            'email'  => 'required|email',
            'status' => 'required'
        ]);

        $user->name   = $request->name;
        $user->email  = $request->email;
        $user->status = $request->status;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // 🔥 SIMPAN LOG
        LogAktivitas::create([
            'id_user' => auth()->id(),
            'aktivitas' => 'Mengedit user: ' . $user->name,
            'waktu_aktivitas' => now()
        ]);

        return redirect()->route('people.index')->with('success', 'User berhasil diupdate');
    }

    // ================== HAPUS USER ==================
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == auth()->id()) {
            return back()->with('error', 'Tidak bisa hapus akun sendiri');
        }

        $nama = $user->name; // simpan dulu sebelum delete

        $user->delete();

        // 🔥 SIMPAN LOG
        LogAktivitas::create([
            'id_user' => auth()->id(),
            'aktivitas' => 'Menghapus user: ' . $nama,
            'waktu_aktivitas' => now()
        ]);

        return back()->with('success', 'User berhasil dihapus');
    }
}