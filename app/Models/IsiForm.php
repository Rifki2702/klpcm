<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IsiForm extends Model
{
    protected $table = 'isi_form';

    protected $fillable = [
        'formulir_id', 
        'isi', 
    ];

    public function formulir()
    {
        return $this->belongsTo(Formulir::class, 'formulir_id');
    }
}
