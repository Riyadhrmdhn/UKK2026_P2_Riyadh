<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $table = 't_tarif';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'jenis_kendaraan',
        'tarif_per_jam'
    ];

    protected function casts(): array
    {
        return [
            'tarif_per_jam' => 'integer',
        ];
    }

    // 🔹 Relasi ke kendaraan
    public function kendaraan()
    {
        return $this->hasMany(Kendaraan::class, 'id_tarif');
    }

    // 🔹 Relasi ke transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_tarif');
    }
}