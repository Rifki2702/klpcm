<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelengkapan extends Model
{
    public function isiForm()
    {
        return $this->belongsTo(IsiForm::class);
    }
    public function formulir()
    {
        return $this->belongsTo(Formulir::class);
    }
}

