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

        $ketepatan = Ketepatan::all(); // Mengambil semua data ketepatan

        // Looping untuk menghitung persentase kualitatif
        foreach ($ketepatan as $ketepatanItem) {
            $hari = Carbon::parse($ketepatanItem->created_at)->dayOfWeek; // Mendapatkan index hari

            $ketepatanHarian = $ketepatan->filter(function ($item) use ($ketepatanItem) {
                return Carbon::parse($item->created_at)->dayOfWeek === Carbon::parse($ketepatanItem->created_at)->dayOfWeek;
            });

            // Hitung jumlah ketepatan harian dan jumlah ketepatan
            $jumlahKetepatanHarian = $ketepatanHarian->count();
            $jumlahKetepatan = $ketepatanHarian->where('ketepatan', true)->count();

            // Hitung persentase kualitatif harian
            $persentaseKualitatif = $jumlahKetepatanHarian > 0 ? round(($jumlahKetepatan / $jumlahKetepatanHarian) * 100, 2) : 0;
            $dataPersentaseKualitatif[$hari] = $persentaseKualitatif;
        }

        // Mengubah format hari dalam $xAxis dengan deskripsi sesuai yang diminta
        $xAxis = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        // Gunakan objek chart yang sudah disiapkan
        return $this->chart->areaChart()
            ->setTitle($title)
            ->setSubtitle($subtitle)
            ->addData('Persentase Ketepatan', array_values($dataPersentaseKualitatif)) // Pastikan data dalam urutan yang benar
            ->setXAxis($xAxis);
    }
}
