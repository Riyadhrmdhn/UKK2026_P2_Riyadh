<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 't_area';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'nama_area',
        'kapasitas',
        'terisi'
    ];

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
            'terisi' => 'integer',
        ];
    }

    // 🔹 Relasi ke transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_area');
    }

    /**
     * 🔥 Helper: sisa slot parkir
     */
    public function sisaSlot()
    {
        return $this->kapasitas - $this->terisi;
    }

    /**
     * 🔥 Helper: status penuh / tidak
     */
    public function isFull()
    {
        return $this->terisi >= $this->kapasitas;
    }
}