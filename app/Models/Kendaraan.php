<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 't_kendaraan';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'plat_kendaraan',
        'warna',
        'status',
        'id_user',
        'id_tarif',
        'created_at'
    ];

    protected $attributes = [
        'status' => null, // 🔥 default NULL
    ];

    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif');
    }
}