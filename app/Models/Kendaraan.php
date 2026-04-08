<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 't_kendaraan';
    protected $primaryKey = 'id';

    public $timestamps = false; // karena hanya ada created_at

    protected $fillable = [
        'plat_kendaraan',
        'warna',
        'status',
        'id_user',
        'id_tarif',
        'created_at'
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
            'created_at' => 'datetime',
        ];
    }

    // 🔹 Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // 🔹 Relasi ke tarif
    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif');
    }

    // 🔹 Relasi ke transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_kendaraan');
    }
}