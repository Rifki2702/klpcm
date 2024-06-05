<?php

namespace App\Charts;

use App\Models\Kelengkapan;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class LengkapTepatChart
{
    protected $chart;

    public function __construct()
    {
        $this->chart = new LarapexChart();
    }

    public function build(): \ArielMejiaDev\LarapexCharts\AreaChart
    {
        $dataPersentaseKuantitatif = array_fill(0, 7, 0); // Inisialisasi array dengan 0 untuk setiap hari

        $tanggalMulai = Carbon::now()->subDays(6); // Mulai dari 7 hari yang lalu
        $kelengkapan = Kelengkapan::where('tglcek', '>=', $tanggalMulai)->get(); // Mengambil data kelengkapan dari 7 hari terakhir

        // Mengubah format x-axis menjadi tanggal dari tglcek
        $xAxis = [];
        for ($i = 0; $i < 7; $i++) {
            $xAxis[] = Carbon::now()->subDays(6 - $i)->format('d M Y');
        }

        // Looping untuk menghitung persentase kuantitatif
        foreach ($kelengkapan as $kelengkapanItem) {
            $tanggal = Carbon::parse($kelengkapanItem->tglcek)->format('d M Y'); // Mendapatkan tanggal
            $index = array_search($tanggal, $xAxis); // Mencari index tanggal pada xAxis

            $kelengkapanHarian = $kelengkapan->filter(function ($item) use ($kelengkapanItem) {
                return Carbon::parse($item->tglcek)->format('d M Y') === Carbon::parse($kelengkapanItem->tglcek)->format('d M Y');
            });

            // Hitung jumlah kelengkapan harian dan jumlah kelengkapan kuantitatif
            $jumlahKelengkapanHarian = $kelengkapanHarian->count();
            $jumlahKuantitatifHarian = $kelengkapanHarian->where('kuantitatif', true)->count();

            // Hitung persentase kuantitatif harian
            $persentaseKuantitatif = $jumlahKelengkapanHarian > 0 ? round(($jumlahKuantitatifHarian / $jumlahKelengkapanHarian) * 100, 2) : 0;
            $dataPersentaseKuantitatif[$index] = $persentaseKuantitatif;
        }
        return $this->chart->areaChart()
            ->setTitle('Rekam Medis')
            ->setSubtitle('Kelengkapan dan Ketepatan')
            ->addData('Persentase Kelengkapan', array_values($dataPersentaseKuantitatif))
            ->addData('Ketepatan', [70, 29, 77, 28, 55, 45])
            ->setXAxis($xAxis);
    }
}
