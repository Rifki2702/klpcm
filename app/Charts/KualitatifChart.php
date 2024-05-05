<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Ketepatan;
use Carbon\Carbon;

class KualitatifChart
{
    protected $chart;

    public function __construct()
    {
        $this->chart = new LarapexChart();
    }

    public function build()
    {
        $title = 'Kualitatif Harian';
        $subtitle = 'Persentase Ketepatan Harian';
        $xAxis = []; // Array untuk menyimpan tanggal-tanggal dari tglcek
        $dataPersentaseKualitatif = [];

        $ketepatan = Ketepatan::all(); // Mengambil semua data ketepatan

        // Looping untuk menghitung persentase kualitatif
        foreach ($ketepatan as $ketepatanItem) {
            $tglCek = Carbon::parse($ketepatanItem->tglcek)->format('Y-m-d');

            if (!in_array($tglCek, $xAxis)) {
                $xAxis[] = $tglCek;
                $index = array_search($tglCek, $xAxis);

                $ketepatanHarian = $ketepatan->filter(function ($ketepatan) use ($tglCek) {
                    return Carbon::parse($ketepatan->tglcek)->format('Y-m-d') === $tglCek;
                });

                // Hitung jumlah ketepatan harian dan jumlah ketepatan
                $jumlahKetepatanHarian = $ketepatanHarian->count();
                $jumlahKetepatan = $ketepatanHarian->where('ketepatan', true)->count();

                // Hitung persentase kualitatif harian
                $persentaseKualitatif = $jumlahKetepatanHarian > 0 ? round(($jumlahKetepatan / $jumlahKetepatanHarian) * 100, 2) : 0;
                $dataPersentaseKualitatif[$index] = $persentaseKualitatif;
            }
        }

        // Gunakan objek chart yang sudah disiapkan
        return $this->chart->areaChart()
            ->setTitle($title)
            ->setSubtitle($subtitle)
            ->addData('Persentase Ketepatan', $dataPersentaseKualitatif)
            ->setXAxis([
                'categories' => $xAxis,
                'type' => 'category',
            ]);
    }
}
