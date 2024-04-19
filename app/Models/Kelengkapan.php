<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelengkapan extends Model
{
    protected $fillable = ['analisis_id', 'formulir_id', 'isi_form_id', 'kuantitatif'];
    use HasFactory;

    protected $table = 'kelengkapan';

    // Mengubah tipe data kuantitatif menjadi array
    protected $casts = [
        'kuantitatif' => 'array',
    ];

    public function isiForm()
    {
        return $this->belongsTo(IsiForm::class);
    }

    public function formulir()
    {
        return $this->belongsTo(Formulir::class);
    }

    public function analisis()
    {
        return $this->belongsTo(Analisis::class);
    }
}
