<?php

namespace App\Http\Controllers;

use App\Models\Analisis;
use Illuminate\Http\Request;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use Mpdf\Mpdf;

class LaporanController extends Controller
{
    public function downloadPDF(Request $request)
    {
        $dataAnalisis = $this->laporanmanagement($request)->getData()['dataAnalisis'];

        $mpdf = new \Mpdf\Mpdf();

        // Tambahkan konten PDF dengan menggunakan data analisis
        $html = view('admin.laporan.downloadpdf', compact('dataAnalisis'))->render();
        $mpdf->WriteHTML($html);

        // Buat nama file sesuai dengan format "analisis_rm_tglberkas.pdf"
        $filename = 'laporan_' . now()->format('dmY') . '.pdf';

        // Output sebagai file PDF dan langsung didownload dengan nama file yang sesuai
        $mpdf->Output($filename, 'D');
    }


    public function downloadExcel(Request $request)
    {
        $data = $this->getData($request);
        return Excel::download(new LaporanExport($data), 'laporan.xlsx');
    }

    public function laporanmanagement(Request $request)
    {
        $filterWaktu = $request->input('filter_waktu');
        $filterDokter = $request->input('filter_dokter');
        $filterStatus = $request->input('filter_status');

        $analisisQuery = Analisis::with(['pasien', 'kelengkapans', 'ketepatans'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', function ($subQuery) {
                    $subQuery->where('name', 'dokter');
                });
            });

        if ($filterWaktu == 'bulanan') {
            $analisisQuery->whereMonth('tglberkas', now()->month);
        } elseif ($filterWaktu == 'tahunan') {
            $analisisQuery->whereYear('tglberkas', now()->year);
        } elseif ($filterWaktu == 'custom') {
            $tanggalAwal = $request->input('tanggal_awal');
            $tanggalAkhir = $request->input('tanggal_akhir');
            $analisisQuery->whereBetween('tglberkas', [$tanggalAwal, $tanggalAkhir]);
        }

        if ($filterDokter) {
            $analisisQuery->where('user_id', $filterDokter);
        }

        if ($filterStatus) {
            $analisisQuery->where(function ($query) use ($filterStatus) {
                $hariIni = now();
                $query->whereHas('kelengkapans', function ($subQuery) use ($filterStatus) {
                    $subQuery->where('kuantitatif', true);
                })->whereHas('ketepatans', function ($subQuery) use ($filterStatus) {
                    $subQuery->where('ketepatan', true);
                });

                if ($filterStatus == 'selesai') {
                    $query->whereRaw('100 = (SELECT ROUND(AVG(CASE WHEN kuantitatif = true THEN 100 ELSE 0 END), 2) FROM kelengkapan WHERE kelengkapan.analisis_id = analisis.id)');
                } elseif ($filterStatus == 'proses') {
                    $query->whereRaw('100 > (SELECT ROUND(AVG(CASE WHEN kuantitatif = true THEN 100 ELSE 0 END), 2) FROM kelengkapan WHERE kelengkapan.analisis_id = analisis.id)')
                        ->whereRaw('DATEDIFF(NOW(), tglcek) < 7');
                } elseif ($filterStatus == 'tertunda') {
                    $query->whereRaw('100 > (SELECT ROUND(AVG(CASE WHEN kuantitatif = true THEN 100 ELSE 0 END), 2) FROM kelengkapan WHERE kelengkapan.analisis_id = analisis.id)')
                        ->whereRaw('DATEDIFF(NOW(), tglcek) >= 7');
                }
            });
        }

        $analisis = $analisisQuery->get();

        $dataAnalisis = $analisis->map(function ($item) {
            if ($item->kelengkapans && $item->ketepatans) {
                $jumlahKuantitatif = $item->kelengkapans->where('kuantitatif', true)->count();
                $jumlahKualitatif = $item->ketepatans->where('ketepatan', true)->count();

                $persentaseKuantitatif = count($item->kelengkapans) > 0 ? round(($jumlahKuantitatif / count($item->kelengkapans)) * 100, 2) : 0;
                $persentaseKualitatif = count($item->ketepatans) > 0 ? round(($jumlahKualitatif / count($item->ketepatans)) * 100, 2) : 0;

                $status = '';
                $hariIni = now();
                $tglCek = \Carbon\Carbon::parse($item->tglcek);

                if ($persentaseKuantitatif == 100) {
                    $status = 'complete';
                } elseif ($persentaseKuantitatif < 100) {
                    $status = 'imr';
                    if ($tglCek->diffInDays($hariIni) >= 7) {
                        $status = 'dmr';
                    }
                }

                return [
                    'analisis' => $item,
                    'persentase' => [
                        'kuantitatif' => $persentaseKuantitatif,
                        'kualitatif' => $persentaseKualitatif
                    ],
                    'status' => $status
                ];
            } else {
                return [
                    'analisis' => $item,
                    'persentase' => [
                        'kuantitatif' => 0,
                        'kualitatif' => 0
                    ],
                    'status' => ''
                ];
            }
        });

        return view('admin.laporan.laporanmanagement', compact('dataAnalisis'));
    }
}
