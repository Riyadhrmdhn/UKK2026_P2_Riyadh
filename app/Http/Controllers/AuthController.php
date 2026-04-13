<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // 🔹 Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 🔹 Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        // ❌ User tidak ditemukan
        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan');
        }

        // ❌ Status nonaktif
        if ($user->status !== 'aktif') {
            return back()->with('error', 'Akun nonaktif');
        }

        // ❌ Password salah
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password salah');
        }

        // 🔥 CEK SHIFT KHUSUS PETUGAS
        if ($user->role === 'petugas') {

            $hour = now()->format('H');

            // 🔥 SHIFT REAL (06-14, 14-22, 22-06)
            if ($hour >= 6 && $hour < 14) {
                $shiftAktif = 0; // pagi
            } elseif ($hour >= 14 && $hour < 22) {
                $shiftAktif = 1; // siang
            } else {
                $shiftAktif = 2; // malam
            }

            // ❌ kalau shift tidak sesuai
            if ($user->shift != $shiftAktif) {
                return back()->with('error', 'Shift anda sedang OFF');
            }
        }

        // ✅ Login
        Auth::login($user);

        // 🔥 Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('dashboard')->with('success', 'Login sebagai Admin');
        } elseif ($user->role === 'owner') {
            return redirect()->route('dashboard')->with('success', 'Login sebagai Owner');
        } else {
            return redirect()->route('dashboard')->with('success', 'Login sebagai Petugas');
        }
    }

    // 🔹 Tampilkan register
    public function showRegister()
    {
        return view('auth.register');
    }

    // 🔹 Proses register
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'petugas',
            'status'   => 'aktif',
            'shift'    => 0 // default pagi
        ]);

        return redirect('/login')->with('success', 'Register berhasil');
    }

    // 🔹 Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Logout berhasil');
    }
}