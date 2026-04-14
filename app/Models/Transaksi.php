<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 't_transaksi';

    public $timestamps = false;

    protected $fillable = [
        'id_kendaraan',
        'id_tarif',
        'waktu_masuk',
        'waktu_keluar',
        'durasi_jam',
        'durasi_menit',
        'biaya_total',
        'status',
        'shift',
        'id_user',
        'id_area'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke Kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
    }

    // Relasi ke Tarif
    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif');
    }

    // Relasi ke Area
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }

    // Relasi ke User (Petugas)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}