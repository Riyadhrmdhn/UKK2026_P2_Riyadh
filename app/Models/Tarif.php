<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    protected $table = 't_tarif';

    public $timestamps = false; // 🔥 FIX ERROR updated_at

    protected $fillable = [
        'jenis_kendaraan',
        'tarif_per_jam'
    ];
}