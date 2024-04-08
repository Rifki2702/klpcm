<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formulir extends Model
{
    protected $table = 'formulir';

    protected $fillable = [
        'nama_formulir',
    ];

    public function isiForms()
    {
        return $this->hasMany(IsiForm::class, 'formulir_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function kelengkapans()
    {
        return $this->hasMany(Kelengkapan::class, 'formulir_id');
    }
}
