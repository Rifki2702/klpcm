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

        $tanggalMulai = Carbon::now()->subDays(6); // Mulai dari 7 hari yang lalu
        $kelengkapan = Kelengkapan::where('created_at', '>=', $tanggalMulai)->get(); // Mengambil data kelengkapan dari 7 hari terakhir

        // Mengubah format x-axis menjadi tanggal dari created_at
        $xAxis = [];
        for ($i = 0; $i < 7; $i++) {
            $xAxis[] = Carbon::now()->subDays(6 - $i)->format('d M Y');
        }

        // Looping untuk menghitung persentase kuantitatif
        foreach ($kelengkapan as $kelengkapanItem) {
            $tanggal = Carbon::parse($kelengkapanItem->created_at)->format('d M Y'); // Mendapatkan tanggal
            $index = array_search($tanggal, $xAxis); // Mencari index tanggal pada xAxis

            $kelengkapanHarian = $kelengkapan->filter(function ($item) use ($kelengkapanItem) {
                return Carbon::parse($item->created_at)->format('d M Y') === Carbon::parse($kelengkapanItem->created_at)->format('d M Y');
            });

            // Hitung jumlah kelengkapan harian dan jumlah kelengkapan kuantitatif
            $jumlahKelengkapanHarian = $kelengkapanHarian->count();
            $jumlahKuantitatifHarian = $kelengkapanHarian->where('kuantitatif', true)->count();

            // Hitung persentase kuantitatif harian
            $persentaseKuantitatif = $jumlahKelengkapanHarian > 0 ? round(($jumlahKuantitatifHarian / $jumlahKelengkapanHarian) * 100, 2) : 0;
            $dataPersentaseKuantitatif[$index] = $persentaseKuantitatif;
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
