<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kualitatif;

class KualitatifSeeder extends Seeder
{
    public function run()
    {
        $kualitatif = [
            'Mutakhir',
            'Tulisan Terbaca',
            'Singkatan Baku',
            'Menghindari Sindiran',
            'Pengisian Tidak Senjang',
            'Tinta',
            'Catatan Jelas'
        ];

        foreach ($kualitatif as $isi) {
            Kualitatif::create(['isi' => $isi]);
        }
    }
}
