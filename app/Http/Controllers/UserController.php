<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 🔹 Tampilkan semua user
    public function index()
    {
        $users = User::latest()->get();
        return view('user.index', compact('users'));
    }

    // 🔹 Form tambah user
    public function create()
    {
        return view('user.create');
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

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    // 🔹 Form edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
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

        return redirect()->route('user.index')
            ->with('success', 'User berhasil diupdate');
    }

    // 🔹 Hapus user
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus');
    }
}