<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\IsiForm;

class Formulir extends Model
{
    protected $table = 'formulir'; // Nama tabel di database

    protected $fillable = [
        'nama_formulir',
    ];

    // Relasi one-to-many dengan tabel isi_form
    public function isiForms()
    {
        return $this->hasMany(IsiForm::class, 'formulir_id');
    }    

}
