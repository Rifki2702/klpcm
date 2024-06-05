<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class RuanganChart
{
    protected $chart;

    public function __construct()
    {
        $this->chart = new LarapexChart();
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        return $this->chart->barChart()
            ->setTitle('Ruangan')
            ->setSubtitle('Kelengkapan Pengisian Setiap Ruangan')
            ->addData('Kelengkapan', ['100', '90', '80']) // Specify the data label
            ->setXAxis(['Mawar', 'Melati', 'Tulip']);
    }
}
