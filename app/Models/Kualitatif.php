<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kualitatif extends Model
{
    protected $table = 'kualitatif';
    protected $fillable = ['isi'];

    public function ketepatans()
    {
        return $this->hasOne(Ketepatan::class);
    }
}
