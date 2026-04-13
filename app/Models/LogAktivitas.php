<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 't_log_aktivitas';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'aktivitas',
        'waktu_aktivitas'
    ];

    // relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}