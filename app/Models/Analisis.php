<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analisis extends Model
{
    use HasFactory;

    protected $table = 'analisis';

    protected $fillable = [
        'user_id',
        'pasien_id',
        'tglberkas',
        'tglcek',
    ];

    // Definisikan relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Definisikan relasi dengan model Pasien
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function kelengkapans()
    {
        return $this->hasMany(Kelengkapan::class);
    }

    public function ketepatans()
    {
        return $this->hasMany(Ketepatan::class);
    }

    // Definisikan relasi dengan model Formulir
    public function formulirs()
    {
        return $this->hasMany(Formulir::class);
    }
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }
}
