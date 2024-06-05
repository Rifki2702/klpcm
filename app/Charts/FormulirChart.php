<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class FormulirChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        return $this->chart->barChart()
            ->setTitle('Formulir')
            ->setSubtitle('Kelengkapan Rekam Medis')
            ->addData('Kelengkapan', ['100', '90', '80']) // Specify the data label
            ->setXAxis(['CPPT', 'Resume Medis', 'Informed Consent']);
    }
}
