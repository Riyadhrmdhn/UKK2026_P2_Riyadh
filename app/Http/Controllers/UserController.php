<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 🔹 Tampilkan semua user (URUTAN FIX)
    public function index()
    {
        $users = User::orderByRaw("
            FIELD(role, 'admin', 'petugas', 'owner')
        ")->get();

        return view('people.index', compact('users'));
    }

    // 🔹 Form tambah user
    public function create()
    {
        return view('people.create');
    }

    // 🔹 Simpan user
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,petugas,owner',
            'status'   => 'required|in:aktif,nonaktif'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status
        ]);

        return redirect()->route('people.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    // 🔹 Form edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('people.edit', compact('user'));
    }

    // 🔹 Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => 'required|max:255',
            'email'  => 'required|email|unique:users,email,' . $id,
            'role'   => 'required|in:admin,petugas,owner',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $data = [
            'name'   => $request->name,
            'email'  => $request->email,
            'role'   => $request->role,
            'status' => $request->status
        ];

        // 🔹 Update password kalau diisi
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('people.index')
            ->with('success', 'User berhasil diupdate');
    }

    // 🔹 Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // ❗ optional: cegah hapus diri sendiri
        if ($user->id == auth()->id()) {
            return back()->with('error', 'Tidak bisa hapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('people.index')
            ->with('success', 'User berhasil dihapus');
    }
}