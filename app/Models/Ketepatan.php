<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ketepatan extends Model
{
    protected $casts = [
        'ketepatan' => 'array',
    ];
    protected $table = 'ketepatan';
    protected $fillable = ['ketepatan', 'kualitatif_id', 'analisis_id'];

    public function kualitatif()
    {
        return $this->belongsTo(Kualitatif::class, 'kualitatif_id');
    }

    public function analisis()
    {
        return $this->belongsTo(Analisis::class, 'analisis_id');
    }
}
