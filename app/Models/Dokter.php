<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokters';

    protected $fillable = ['nama_dokter'];

    public function analisis()
    {
        return $this->hasMany(Analisis::class);
    }
}
