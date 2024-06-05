<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class DokterChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        return $this->chart->barChart()
            ->setTitle('Dokter')
            ->setSubtitle('Kelengkapan Pengisian RM Dokter')
            ->addData('Kelengkapan', ['100', '90', '80']) // Specify the data label
            ->setXAxis(['dr.A', 'dr.B', 'dr.C']);
    }
}
