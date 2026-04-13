<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $log = LogAktivitas::with('user')
            ->orderBy('waktu_aktivitas', 'desc')
            ->get();

        return view('log.index', compact('log'));
    }
}