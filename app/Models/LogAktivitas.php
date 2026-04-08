<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 't_transaksi';
    protected $primaryKey = 'id';

    public $timestamps = false;

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

    protected function casts(): array
    {
        return [
            'waktu_masuk' => 'datetime',
            'waktu_keluar' => 'datetime',
            'status' => 'string',
        ];
    }

    // 🔹 Relasi ke kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
    }

    // 🔹 Relasi ke user (petugas)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // 🔹 Relasi ke tarif
    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif');
    }

    // 🔹 Relasi ke area
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }
}