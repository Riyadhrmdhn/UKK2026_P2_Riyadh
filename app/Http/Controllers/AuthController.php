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
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (Auth::attempt($credentials, $request->remember)) {

                $user = Auth::user();

                // 🔥 CEK SHIFT PETUGAS
                if ($user->role === 'petugas') {

                    $hour = now()->format('H');

                    if ($hour >= 6 && $hour < 14) {
                        $shiftAktif = 'pagi';
                    } elseif ($hour >= 14 && $hour < 22) {
                        $shiftAktif = 'siang';
                    } else {
                        $shiftAktif = 'malam';
                    }

                    if ($user->shift !== $shiftAktif) {
                        Auth::logout();
                        return back()->with('error', 'Shift anda sedang OFF');
                    }
                }

                return redirect()->route('dashboard');
            }

            return back()->with('error', 'Email atau password salah');
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