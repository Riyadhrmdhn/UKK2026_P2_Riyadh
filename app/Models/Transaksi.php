<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 't_transaksi';

    protected $fillable = [
        'id_kendaraan',
        'id_tarif',
        'waktu_masuk',
        'waktu_keluar',
        'durasi_jam',
        'durasi_menit',
        'durasi',
        'biaya_total',
        'status',
        'id_user',
        'id_area'
    ];

    public $timestamps = false;

    // 🔹 Relasi ke kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(\App\Models\Kendaraan::class, 'id_kendaraan');
    }

    // 🔹 Relasi ke user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user');
    }
}