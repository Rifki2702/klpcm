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
        $dataPersentaseKuantitatif = array_fill(0, 7, 0); // Inisialisasi array dengan 0 untuk setiap hari

        $kelengkapan = Kelengkapan::all(); // Mengambil semua data kelengkapan

        // Looping untuk menghitung persentase kuantitatif
        foreach ($kelengkapan as $kelengkapanItem) {
            $hari = Carbon::parse($kelengkapanItem->tglcek)->dayOfWeek; // Mendapatkan index hari

            $kelengkapanHarian = $kelengkapan->filter(function ($item) use ($kelengkapanItem) {
                return Carbon::parse($item->tglcek)->dayOfWeek === Carbon::parse($kelengkapanItem->tglcek)->dayOfWeek;
            });

            // Hitung jumlah kelengkapan harian dan jumlah kelengkapan kuantitatif
            $jumlahKelengkapanHarian = $kelengkapanHarian->count();
            $jumlahKuantitatifHarian = $kelengkapanHarian->where('kuantitatif', true)->count();

            // Hitung persentase kuantitatif harian
            $persentaseKuantitatif = $jumlahKelengkapanHarian > 0 ? round(($jumlahKuantitatifHarian / $jumlahKelengkapanHarian) * 100, 2) : 0;
            $dataPersentaseKuantitatif[$hari] = $persentaseKuantitatif;
        }

        // Mengubah format hari dalam $xAxis dengan deskripsi sesuai yang diminta
        $xAxis = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        // Gunakan objek chart yang sudah disiapkan
        return $this->chart->areaChart()
            ->setTitle($title)
            ->setSubtitle($subtitle)
            ->addData('Persentase Kelengkapan', array_values($dataPersentaseKuantitatif)) // Pastikan data dalam urutan yang benar
            ->setXAxis($xAxis);
    }
}
