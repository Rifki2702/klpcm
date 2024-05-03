<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Analisis;
use Carbon\Carbon;

class KuantitatifChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(string $timeFrame = 'monthly'): \ArielMejiaDev\LarapexCharts\AreaChart
    {
        $title = 'Kuantitatif';
        $subtitle = 'Persentase Kelengkapan Kuantitatif';
        $xAxis = [];
        $dataPersentaseKuantitatif = [];

        switch ($timeFrame) {
            case 'daily':
                $title .= ' Harian';
                $subtitle .= ' Harian';
                $xAxis = range(1, 7); // Data harian selama satu minggu
                break;
            case 'monthly':
                $title .= ' Bulanan';
                $subtitle .= ' Bulanan';
                $xAxis = range(1, 6); // Data bulanan selama 6 bulan
                break;
            case 'yearly':
                $title .= ' Tahunan';
                $subtitle .= ' Tahunan';
                $xAxis = range(Carbon::now()->year - 4, Carbon::now()->year); // Data tahunan selama 5 tahun
                break;
        }

        $analisis = Analisis::all(); // Mengambil semua data analisis

        // Looping untuk menghitung persentase kuantitatif
        foreach ($xAxis as $index => $label) {
            $dataPersentaseKuantitatif[$index] = $analisis->filter(function ($analisis) use ($label) {
                // Pastikan bahwa 'tglcek' adalah instance dari Carbon sebelum memformat
                if ($analisis->tglcek instanceof Carbon) {
                    return $analisis->tglcek->format('m') == $label;
                }
                return false;
            })->avg('persentase_kuantitatif');
        }

        // Buat chart baru menggunakan LarapexChart
        $chart = new LarapexChart();

        // Atur tipe sumbu x sesuai dengan pilihan waktu
        $xAxisType = $timeFrame === 'yearly' ? 'datetime' : 'category';

        return $chart->areaChart()
            ->setTitle($title)
            ->setSubtitle($subtitle)
            ->addData('Persentase Kelengkapan', $dataPersentaseKuantitatif)
            ->setXAxis([
                'categories' => $xAxis,
                'type' => $xAxisType,
            ]);
    }
}
