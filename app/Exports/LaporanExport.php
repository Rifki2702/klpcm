<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $dataAnalisis;

    public function __construct(Collection $dataAnalisis)
    {
        $this->dataAnalisis = $dataAnalisis;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = collect([]);

        foreach ($this->dataAnalisis as $key => $item) {
            $data->push([
                'No' => $key + 1,
                'No RM' => $item['analisis']->pasien->rm,
                'Nama' => $item['analisis']->pasien->name,
                'Tgl Berkas' => \Carbon\Carbon::parse($item['analisis']->tglberkas)->format('d/m/Y'),
                'Tgl Analisis' => \Carbon\Carbon::parse($item['analisis']->tglcek)->format('d/m/Y'),
                'Kuantitatif (%)' => $item['persentase']['kuantitatif'] . '%',
                'Kualitatif (%)' => $item['persentase']['kualitatif'] . '%',
                'Dokter' => $item['analisis']->user ? $item['analisis']->user->name : '',
                'Status' => $this->getStatusBadge($item['status']),
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'No RM',
            'Nama',
            'Tgl Berkas',
            'Tgl Analisis',
            'Kuantitatif (%)',
            'Kualitatif (%)',
            'Dokter',
            'Status',
        ];
    }

    public function title(): string
    {
        return 'Laporan';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFA0A0A0',
                ],
            ],
        ]);
    }

    private function getStatusBadge($status)
    {
        switch ($status) {
            case 'complete':
                return 'Complete';
            case 'imr':
                return 'IMR';
            case 'dmr':
                return 'DMR';
            default:
                return '';
        }
    }
}
