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
        $dataPersentaseKualitatif = array_fill(0, 7, 0); // Inisialisasi array dengan 0 untuk setiap hari

        $tanggalMulai = Carbon::now()->subDays(6); // Mulai dari 7 hari yang lalu
        $ketepatan = Ketepatan::where('created_at', '>=', $tanggalMulai)->get(); // Mengambil data ketepatan dari 7 hari terakhir

        // Mengubah format x-axis menjadi tanggal dari created_at
        $xAxis = [];
        for ($i = 0; $i < 7; $i++) {
            $xAxis[] = Carbon::now()->subDays(6 - $i)->format('d M Y');
        }

        // Looping untuk menghitung persentase kualitatif
        foreach ($ketepatan as $ketepatanItem) {
            $tanggal = Carbon::parse($ketepatanItem->created_at)->format('d M Y'); // Mendapatkan tanggal
            $index = array_search($tanggal, $xAxis); // Mencari index tanggal pada xAxis

            $ketepatanHarian = $ketepatan->filter(function ($item) use ($ketepatanItem) {
                return Carbon::parse($item->created_at)->format('d M Y') === Carbon::parse($ketepatanItem->created_at)->format('d M Y');
            });

            // Hitung jumlah ketepatan harian dan jumlah ketepatan
            $jumlahKetepatanHarian = $ketepatanHarian->count();
            $jumlahKetepatan = $ketepatanHarian->where('ketepatan', true)->count();

            // Hitung persentase kualitatif harian
            $persentaseKualitatif = $jumlahKetepatanHarian > 0 ? round(($jumlahKetepatan / $jumlahKetepatanHarian) * 100, 2) : 0;
            $dataPersentaseKualitatif[$index] = $persentaseKualitatif;
        }

        // Gunakan objek chart yang sudah disiapkan
        return $this->chart->areaChart()
            ->setTitle($title)
            ->setSubtitle($subtitle)
            ->addData('Persentase Ketepatan', array_values($dataPersentaseKualitatif)) // Pastikan data dalam urutan yang benar
            ->setXAxis($xAxis);
    }
}
