<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Kelengkapan;
use Carbon\Carbon;

class KuantitatifChart
{
    protected $chart;

    public function __construct()
    {
        $this->chart = new LarapexChart();
    }

    public function build()
    {
        $title = 'Kuantitatif Harian';
        $subtitle = 'Persentase Kelengkapan Kuantitatif Harian';
        $xAxis = []; // Array untuk menyimpan nama hari atau tanggal
        $dataPersentaseKuantitatif = [];

        $kelengkapan = Kelengkapan::all(); // Mengambil semua data kelengkapan

        // Looping untuk menghitung persentase kuantitatif
        foreach ($kelengkapan as $kelengkapanItem) {
            $tglCek = Carbon::parse($kelengkapanItem->tglcek);
            $namaHari = $tglCek->isoFormat('dddd'); // Mengambil nama hari dari tglcek
            $tanggal = $tglCek->format('d M'); // Mengambil tanggal dari tglcek

            $label = "$namaHari, $tanggal";

            if (!in_array($label, $xAxis)) {
                $xAxis[] = $label;
                $index = array_search($label, $xAxis);

                $kelengkapanHarian = $kelengkapan->filter(function ($kelengkapan) use ($tglCek) {
                    return Carbon::parse($kelengkapan->tglcek)->isSameDay($tglCek);
                });

                // Hitung jumlah kelengkapan harian dan jumlah kelengkapan kuantitatif
                $jumlahKelengkapanHarian = $kelengkapanHarian->count();
                $jumlahKuantitatifHarian = $kelengkapanHarian->where('kuantitatif', true)->count();

                // Hitung persentase kuantitatif harian
                $persentaseKuantitatif = $jumlahKelengkapanHarian > 0 ? round(($jumlahKuantitatifHarian / $jumlahKelengkapanHarian) * 100, 2) : 0;
                $dataPersentaseKuantitatif[$index] = $persentaseKuantitatif;
            }
        }

        // Gunakan objek chart yang sudah disiapkan
        return $this->chart->areaChart()
            ->setTitle($title)
            ->setSubtitle($subtitle)
            ->addData('Persentase Kelengkapan', $dataPersentaseKuantitatif)
            ->setXAxis([
                'categories' => $xAxis,
                'type' => 'category',
            ]);
    }
}
