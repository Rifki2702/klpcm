<?php

namespace App\Http\Controllers;

use App\Models\Analisis;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:dokter']);
    }

    public function viewklpcm()
    {
        $user_id = auth()->id();
        $analisisKurangDariSeratus = Analisis::with(['pasien', 'kelengkapans'])
            ->where('user_id', $user_id)
            ->whereHas('kelengkapans', function ($query) {
                $query->where('kuantitatif', true);
            })
            ->get()
            ->filter(function ($analisa) {
                $totalKelengkapan = $analisa->kelengkapans->count();
                $jumlahKuantitatif = $analisa->kelengkapans->where('kuantitatif', true)->count();
                $analisa['persentaseKuantitatif'] = $totalKelengkapan > 0 ? round(($jumlahKuantitatif / $totalKelengkapan) * 100, 2) : 0;
                return $analisa['persentaseKuantitatif'] < 100;
            });

        return view('dokter.viewklpcm', compact('analisisKurangDariSeratus'));
    }
}
