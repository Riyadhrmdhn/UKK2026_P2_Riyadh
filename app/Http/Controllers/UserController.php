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
    $users = User::all();

    $admin = $users->where('role', 'admin')->values();
    $petugas = $users->where('role', 'petugas')->values();
    $owner = $users->where('role', 'owner')->values();

    $hour = now()->format('H');

    if ($hour >= 6 && $hour < 14) {
        $shiftAktif = 0;
    } elseif ($hour >= 14 && $hour < 22) {
        $shiftAktif = 1;
    } else {
        $shiftAktif = 2;
    }

    return view('people.index', compact(
        'users',
        'admin',
        'petugas',
        'owner',
        'shiftAktif'
    ));
}

    // ================== TAMBAH USER ==================
public function store(Request $request)
{
    $request->validate([
        'name'         => 'required',
        'email'        => 'required|email|unique:users,email',
        'password'     => 'required|min:6',
        'role'         => 'required',
        'shift'        => 'nullable',
        'status_kerja' => 'required'
    ]);

    $shift = null;
    $status_shift = 'aktif';

    if ($request->role === 'petugas') {

        if (!$request->shift) {
            return back()->with('error', 'Shift wajib dipilih untuk petugas');
        }

        $shift = $request->shift;
        $hour  = now()->format('H');

        if ($hour >= 6 && $hour < 14) {
            $shiftSekarang = 'pagi';
        } elseif ($hour >= 14 && $hour < 22) {
            $shiftSekarang = 'siang';
        } else {
            $shiftSekarang = 'malam';
        }

        $status_shift = ($shift === $shiftSekarang) ? 'aktif' : 'nonaktif';
    }

    // 🔥 STATUS FINAL = GABUNGAN
    $status_final = ($status_shift === 'aktif' && $request->status_kerja === 'aktif')
                    ? 'aktif'
                    : 'nonaktif';

    $user = User::create([
        'name'         => $request->name,
        'email'        => $request->email,
        'password'     => Hash::make($request->password),
        'role'         => $request->role,
        'shift'        => $shift,
        'status'       => $status_final,
        'status_kerja' => $request->status_kerja
    ]);

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
        'name'         => 'required',
        'email'        => 'required|email',
        'role'         => 'required',
        'shift'        => 'nullable',
        'status_kerja' => 'required'
    ]);

    $shift = null;
    $status_shift = 'aktif';

    if ($request->role === 'petugas') {

        $shift = $request->shift;
        $hour  = now()->format('H');

        if ($hour >= 6 && $hour < 14) {
            $shiftSekarang = 'pagi';
        } elseif ($hour >= 14 && $hour < 22) {
            $shiftSekarang = 'siang';
        } else {
            $shiftSekarang = 'malam';
        }

        $status_shift = ($shift === $shiftSekarang) ? 'aktif' : 'nonaktif';
    }

    $status_final = ($status_shift === 'aktif' && $request->status_kerja === 'aktif')
                    ? 'aktif'
                    : 'nonaktif';

    $user->update([
        'name'         => $request->name,
        'email'        => $request->email,
        'role'         => $request->role,
        'shift'        => $shift,
        'status'       => $status_final,
        'status_kerja' => $request->status_kerja,
        'password'     => $request->filled('password')
                            ? Hash::make($request->password)
                            : $user->password
    ]);

    LogAktivitas::create([
        'id_user' => auth()->id(),
        'aktivitas' => 'Mengedit user: ' . $user->name,
        'waktu_aktivitas' => now()
    ]);

    return redirect()->route('people.index')
            ->with('success', 'User berhasil diupdate');
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