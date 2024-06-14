<?php

namespace App\Http\Controllers;

use App\Charts\DokterChart;
use App\Charts\FormulirChart;
use App\Charts\KualitatifChart;
use App\Charts\LengkapTepatChart;
use App\Charts\RuanganChart;
use Carbon\Carbon;
use App\Models\Analisis;
use Illuminate\Http\Request;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use App\Models\Dokter;
use App\Models\Formulir;
use App\Models\Kelengkapan;
use App\Models\Ketepatan;
use App\Models\Kualitatif;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use ArielMejiaDev\LarapexCharts\LarapexChart as LarapexChartsLarapexChart;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class LaporanController extends Controller
{
    public function downloadPDF(Request $request)
    {
        $dataAnalisis = $this->laporanmanagement($request)->getData()['dataAnalisis'];
        return view('admin.laporan.downloadpdf', compact('dataAnalisis'));

        $filename = 'laporan_' . now()->format('dmY') . '.pdf';
    }

    public function downloadExcel(Request $request)
    {
        // Ambil data analisis dengan cara yang sama seperti dalam `downloadPDF`
        $dataAnalisis = $this->laporanmanagement($request)->getData()['dataAnalisis'];

        // Buat nama file sesuai dengan format "laporan_ddmmyyyy.xlsx"
        $filename = 'laporan_' . now()->format('dmY') . '.xlsx';

        // Unduh file Excel dengan data yang sesuai
        return Excel::download(new LaporanExport(collect($dataAnalisis)), $filename);
    }

    public function laporanmanagement(Request $request)
    {
        $filterWaktu = $request->input('filter_waktu');
        $filterRuangan = $request->input('filter_ruangan');
        $filterStatus = $request->input('filter_status');

        $analisisQuery = Analisis::with(['pasien', 'kelengkapans', 'ketepatans'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', function ($subQuery) {
                    $subQuery->where('name', 'ruangan');
                });
            });

        if ($filterWaktu == 'bulanan') {
            $bulan = $request->input('bulan') ? Carbon::parse($request->input('bulan'))->month : now()->month;
            $tahun = $request->input('bulan') ? Carbon::parse($request->input('bulan'))->year : now()->year;
            $analisisQuery->whereMonth('tglberkas', $bulan)
                ->whereYear('tglberkas', $tahun);
        } elseif ($filterWaktu == 'tahunan') {
            $tahun = $request->input('tahun') ?: now()->year;
            $analisisQuery->whereYear('tglberkas', $tahun);
        } elseif ($filterWaktu == 'custom') {
            $tanggalAwal = $request->input('tanggal_awal');
            $tanggalAkhir = $request->input('tanggal_akhir');
            $analisisQuery->whereBetween('tglberkas', [$tanggalAwal, $tanggalAkhir]);
        }

        if ($filterRuangan) {
            $analisisQuery->where('user_id', $filterRuangan);
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
                        ->whereRaw('DATEDIFF(NOW(), created_at) < 14');
                } elseif ($filterStatus == 'tertunda') {
                    $query->whereRaw('100 > (SELECT ROUND(AVG(CASE WHEN kuantitatif = true THEN 100 ELSE 0 END), 2) FROM kelengkapan WHERE kelengkapan.analisis_id = analisis.id)')
                        ->whereRaw('DATEDIFF(NOW(), created_at) >= 14');
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
                $created_at = \Carbon\Carbon::parse($item->created_at);

                if ($persentaseKuantitatif == 100) {
                    $status = 'complete';
                } elseif ($persentaseKuantitatif < 100) {
                    $status = 'imr';
                    if ($created_at->diffInDays($hariIni) >= 7) {
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

    public function laporanFormulir(Request $request)
    {
        // Mengambil semua formulir
        $formulirs = Formulir::all(); // Pastikan menggunakan model yang tepat untuk mengambil data formulir
        $formulirId = $request->input('formulir_id', null);
        $filterWaktu = $request->input('filter_waktu', null);
        $bulan = $request->input('bulan', null);
        $tahun = $request->input('tahun', null);
        $tanggalAwal = $request->input('tanggal_awal', null);
        $tanggalAkhir = $request->input('tanggal_akhir', null);

        // Mengatur query awal
        $query = Formulir::query();

        // Menambahkan kondisi jika formulir dipilih
        if ($formulirId) {
            $query->where('id', $formulirId);
        }

        // Menambahkan filter waktu
        $query->whereHas('isiForms.kelengkapan', function ($query) use ($filterWaktu, $bulan, $tahun, $tanggalAwal, $tanggalAkhir) {
            if ($filterWaktu) {
                if ($filterWaktu == 'bulanan' && $bulan) {
                    $query->whereMonth('created_at', '=', date('m', strtotime($bulan)))
                        ->whereYear('created_at', '=', date('Y', strtotime($bulan)));
                } elseif ($filterWaktu == 'tahunan' && $tahun) {
                    $query->whereYear('created_at', '=', $tahun);
                } elseif ($filterWaktu == 'custom' && $tanggalAwal && $tanggalAkhir) {
                    $query->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
                }
            }
        });

        // Mengambil formulir dengan relasi isiForms dan menghitung kelengkapan
        $formulirs = $query->with(['isiForms' => function ($query) {
            $query->withCount(['kelengkapan as jumlah_lengkap' => function ($query) {
                $query->where('kuantitatif', true);
            }]);
            $query->withCount('kelengkapan');
        }])->get();

        // Memetakan data formulir untuk tampilan
        $dataFormulir = $formulirs->map(function ($formulir) {
            return [
                'nama_formulir' => $formulir->nama_formulir,
                'isi_formulir' => $formulir->isiForms->map(function ($isiForm) {
                    $persentaseLengkap = $isiForm->kelengkapan_count > 0 ? round(($isiForm->jumlah_lengkap / $isiForm->kelengkapan_count) * 100, 2) : 0;
                    return [
                        'isi' => $isiForm->isi,
                        'persentase_lengkap' => $persentaseLengkap
                    ];
                })
            ];
        });

        // Mengirim data ke tampilan
        return view('admin.laporan.laporanformulir', compact('formulirs', 'dataFormulir', 'formulirId', 'filterWaktu', 'bulan', 'tahun', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function laporanformulirpdf(Request $request)
    {
        // Mengambil semua formulir
        $formulirs = Formulir::all();
        $formulirId = $request->input('formulir_id', null);
        $filterWaktu = $request->input('filter_waktu', null);
        $bulan = $request->input('bulan', null);
        $tahun = $request->input('tahun', null);
        $tanggalAwal = $request->input('tanggal_awal', null);
        $tanggalAkhir = $request->input('tanggal_akhir', null);

        // Mengatur query awal
        $query = Formulir::query();

        // Menambahkan kondisi jika formulir dipilih
        if ($formulirId) {
            $query->where('id', $formulirId);
        }

        // Menambahkan filter waktu
        $query->whereHas('isiForms.kelengkapan', function ($query) use ($filterWaktu, $bulan, $tahun, $tanggalAwal, $tanggalAkhir) {
            if ($filterWaktu) {
                if ($filterWaktu == 'bulanan' && $bulan) {
                    $query->whereMonth('created_at', '=', date('m', strtotime($bulan)))
                        ->whereYear('created_at', '=', date('Y', strtotime($bulan)));
                } elseif ($filterWaktu == 'tahunan' && $tahun) {
                    $query->whereYear('created_at', '=', $tahun);
                } elseif ($filterWaktu == 'custom' && $tanggalAwal && $tanggalAkhir) {
                    $query->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
                }
            }
        });

        // Mengambil formulir dengan relasi isiForms dan menghitung kelengkapan
        $formulirs = $query->with(['isiForms' => function ($query) {
            $query->withCount(['kelengkapan as jumlah_lengkap' => function ($query) {
                $query->where('kuantitatif', true);
            }]);
            $query->withCount('kelengkapan');
        }])->get();

        // Memetakan data formulir untuk tampilan
        $dataFormulir = $formulirs->map(function ($formulir) {
            return [
                'nama_formulir' => $formulir->nama_formulir,
                'isi_formulir' => $formulir->isiForms->map(function ($isiForm) {
                    $persentaseLengkap = $isiForm->kelengkapan_count > 0 ? round(($isiForm->jumlah_lengkap / $isiForm->kelengkapan_count) * 100, 2) : 0;
                    return [
                        'isi' => $isiForm->isi,
                        'persentase_lengkap' => $persentaseLengkap
                    ];
                })
            ];
        });

        // Mengirim data ke tampilan
        return view('admin.laporan.laporanformulir_pdf', compact('dataFormulir'));
    }

    public function laporankualitatif(Request $request)
    {
        $filterWaktu = $request->input('filter_waktu', null);
        $bulan = $request->input('bulan', null);
        $tahun = $request->input('tahun', null);
        $tanggalAwal = $request->input('tanggal_awal', null);
        $tanggalAkhir = $request->input('tanggal_akhir', null);

        // Mengatur query awal
        $query = Kualitatif::with('ketepatans');

        // Menambahkan filter waktu pada relasi ketepatan
        $query->whereHas('ketepatans', function ($q) use ($filterWaktu, $bulan, $tahun, $tanggalAwal, $tanggalAkhir) {
            if ($filterWaktu) {
                if ($filterWaktu == 'bulanan' && $bulan) {
                    $q->whereMonth('created_at', '=', date('m', strtotime($bulan)))
                        ->whereYear('created_at', '=', date('Y', strtotime($bulan)));
                } elseif ($filterWaktu == 'tahunan' && $tahun) {
                    $q->whereYear('created_at', '=', $tahun);
                } elseif ($filterWaktu == 'custom' && $tanggalAwal && $tanggalAkhir) {
                    $q->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
                }
            }
        });

        // Mengambil data kualitatif dengan relasi ketepatan
        $kualitatif = $query->with('ketepatans')->get(); // Menghapus relasi 'isi' yang tidak didefinisikan

        // Mengelompokkan data berdasarkan kualitatif_id dan menghitung persentase ketepatan
        $dataAnalisis = $kualitatif->mapWithKeys(function ($item) {
            $totalKetepatan = $item->ketepatans->count();
            $jumlahKualitatif = $item->ketepatans->where('ketepatan', true)->count();
            $persentaseKetepatan = $totalKetepatan > 0 ? round(($jumlahKualitatif / $totalKetepatan) * 100, 2) : 0;

            return [
                $item->id => [
                    'isi' => $item->isi, // Memastikan kolom 'isi' dipanggil dengan benar
                    'persentaseKetepatan' => $persentaseKetepatan
                ]
            ];
        });

        // Mengirim data ke tampilan
        return view('admin.laporan.laporankualitatif', compact('dataAnalisis', 'filterWaktu', 'bulan', 'tahun', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function laporangrafik(Request $request)
    {
        $filterWaktu = $request->input('filter_waktu', null);
        $bulanRequest = $request->input('bulan', null);
        $bulan = null;
        if ($bulanRequest) {
            $date = Carbon::createFromFormat('Y-m', $bulanRequest);
            $bulan = str_pad($date->month, 2, '0', STR_PAD_LEFT);
        }
        $tahun = $request->input('tahun', null);
        $tanggalAwal = $request->input('tanggal_awal', null);
        $tanggalAkhir = $request->input('tanggal_akhir', null);

        $analisis = Analisis::whereHas('user.roles', function ($query) {
            $query->where('name', 'dokter');
        })->get();

        $data['dataAnalisis'] = $analisis->map(function ($item) {
            return [
                'analisis' => $item
            ];
        });
        // Mengambil data untuk chart kuantitatif
        $data['LengkapTepatChart'] = $this->chartLengkap($tanggalAwal, $tanggalAkhir, $filterWaktu, $bulan, $tahun);
        $data['DokterChart'] = $this->DokterChart($tanggalAwal, $tanggalAkhir, $filterWaktu, $bulan, $tahun);
        $data['RuanganChart'] = $this->RuanganChart($tanggalAwal, $tanggalAkhir, $filterWaktu, $bulan, $tahun);
        $data['FormulirChart'] = $this->FormulirChart($tanggalAwal, $tanggalAkhir, $filterWaktu, $bulan, $tahun);

        $data['filter_waktu'] = $filterWaktu;
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['tanggal_awal'] = $tanggalAwal;
        $data['tanggal_akhir'] = $tanggalAkhir;
        return view('admin.laporan.laporangrafik', $data);
    }

    public function laporangrafikPDF(Request $request)
    {
        $filterWaktu = $request->input('filter_waktu', null);
        $bulanRequest = $request->input('bulan', null);
        $bulan = null;
        if ($bulanRequest) {
            $date = Carbon::createFromFormat('Y-m', $bulanRequest);
            $bulan = str_pad($date->month, 2, '0', STR_PAD_LEFT);
        }
        $tahun = $request->input('tahun', null);
        $tanggalAwal = $request->input('tanggal_awal', null);
        $tanggalAkhir = $request->input('tanggal_akhir', null);
        // Mengambil data untuk chart kuantitatif
        $data['LengkapTepatChart'] = $this->chartLengkap($tanggalAwal, $tanggalAkhir, $filterWaktu, $bulan, $tahun);
        $data['DokterChart'] = $this->DokterChart($tanggalAwal, $tanggalAkhir, $filterWaktu, $bulan, $tahun);
        $data['RuanganChart'] = $this->RuanganChart($tanggalAwal, $tanggalAkhir, $filterWaktu, $bulan, $tahun);
        $data['FormulirChart'] = $this->FormulirChart($tanggalAwal, $tanggalAkhir, $filterWaktu, $bulan, $tahun);
        $data['filter_waktu'] = $filterWaktu;
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['tanggal_awal'] = $tanggalAwal;
        $data['tanggal_akhir'] = $tanggalAkhir;
        return view('admin.laporan.laporangrafik_pdf', $data);
    }

    public function chartLengkap($tanggalAwal, $tanggalAkhir, $filterWaktu, $bulan, $tahun)
    {
        $chart = new LarapexChartsLarapexChart();
        $dataPersentaseKuantitatif = array_fill(0, 7, 0); // Inisialisasi array dengan 0 untuk setiap hari
        $dataPersentaseKualitatif = array_fill(0, 7, 0);
        $tanggalMulai = Carbon::now()->subDays(6); // Mulai dari 7 hari yang lalu
        if ($tanggalAwal != null || $tanggalAkhir != null) {
            $kelengkapan = Kelengkapan::whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->get(); // Mengambil data kelengkapan dari 7 hari terakhir
            $ketepatan = Ketepatan::whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->get();
        } elseif ($bulan != null) {
            $kelengkapan = Kelengkapan::whereMonth('created_at', '=', $bulan)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
            $ketepatan = Ketepatan::whereMonth('created_at', '=', $bulan)->get();
        } elseif ($tahun != null) {
            $kelengkapan = Kelengkapan::whereYear('created_at', '=', $tahun)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
            $ketepatan = Ketepatan::whereYear('created_at', '=', $tahun)->get();
        } elseif ($filterWaktu != null) {
            $kelengkapan = Kelengkapan::whereDate('created_at', '=', $filterWaktu)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
            $ketepatan = Ketepatan::whereDate('created_at', '=', $filterWaktu)->get();
        } else {
            $ketepatan = Ketepatan::where('created_at', '>=', $tanggalMulai)->get();
            $kelengkapan = Kelengkapan::where('created_at', '>=', $tanggalMulai)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        }

        // Mengubah format x-axis menjadi tanggal dari created_at
        $xAxis = [];
        if ($tanggalAwal != null || $tanggalAkhir != null) {
            $tanggalMulaiRequest = Carbon::parse($tanggalAwal)->startOfDay();
            $tanggalAkhirRequest = Carbon::parse($tanggalAkhir)->endOfDay();
            for ($date = $tanggalMulaiRequest; $date->lte($tanggalAkhirRequest); $date->addDay()) {
                $xAxis[] = $date->format('d M Y');
            }
        } else {
            for ($i = 0; $i < 7; $i++) {
                $xAxis[] = Carbon::now()->subDays(6 - $i)->format('d M Y');
            }
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

        // Mengambil data untuk chart kuantitatif
        return $chart->areaChart()
            ->setTitle('Rekam Medis')
            ->setSubtitle('Kelengkapan dan Ketepatan')
            ->addData('Persentase Kelengkapan', array_values($dataPersentaseKuantitatif))
            ->addData('Ketepatan', array_values($dataPersentaseKualitatif))
            ->setXAxis($xAxis);
    }

    public function DokterChart($tanggalAwal, $tanggalAkhir, $filterWaktu, $bulan, $tahun)
    {
        $chart = new LarapexChartsLarapexChart();
        $dokter = Dokter::get();
        $array = [];
        $tanggalMulai = Carbon::now()->subDays(6);
        if ($tanggalAwal != null || $tanggalAkhir != null) {
            $kelengkapan = Kelengkapan::with('analisis')->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->get(); // Mengambil data kelengkapan dari 7 hari terakhir

        } elseif ($bulan != null) {
            $kelengkapan = Kelengkapan::with('analisis')->whereMonth('created_at', '=', $bulan)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        } elseif ($tahun != null) {
            $kelengkapan = Kelengkapan::with('analisis')->whereYear('created_at', '=', $tahun)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        } elseif ($filterWaktu != null) {
            $kelengkapan = Kelengkapan::with('analisis')->whereDate('created_at', '=', $filterWaktu)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        } else {
            $kelengkapan = Kelengkapan::with('analisis')->where('created_at', '>=', $tanggalMulai)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        }
        foreach ($dokter as $key => $value) {
            $array[] = $value->nama_dokter;
        }
        $dataPersentaseKuantitatif = array_fill(0, count($dokter), 0);

        foreach ($dokter as $key => $dokterItem) {
            $dokterId = $dokterItem->id;

            $kelengkapanDokter = $kelengkapan->filter(function ($item) use ($dokterId) {
                return $item->analisis->dokter_id === $dokterId;
            });

            foreach ($kelengkapanDokter as $kelengkapanItem) {

                $kelengkapanHarian = $kelengkapanDokter->filter(function ($item) use ($kelengkapanItem) {
                    return Carbon::parse($item->created_at)->format('d M Y') === Carbon::parse($kelengkapanItem->created_at)->format('d M Y');
                });

                $jumlahKelengkapanHarian = $kelengkapanHarian->count();
                $jumlahKuantitatifHarian = $kelengkapanHarian->where('kuantitatif', true)->count();

                $persentaseKuantitatif = $jumlahKelengkapanHarian > 0 ? round(($jumlahKuantitatifHarian / $jumlahKelengkapanHarian) * 100, 2) : 0;
                $dataPersentaseKuantitatif[$key] = $persentaseKuantitatif;
            }
        }

        return $chart->barChart()
            ->setTitle('Dokter')
            ->setSubtitle('Kelengkapan Pengisian RM Dokter')
            ->addData('Kelengkapan', $dataPersentaseKuantitatif) // Specify the data label
            ->setXAxis($array);
    }

    public function RuanganChart($tanggalAwal, $tanggalAkhir, $filterWaktu, $bulan, $tahun)
    {
        $chart = new LarapexChartsLarapexChart();

        $array = [];

        $tanggalMulai = Carbon::now()->subDays(6);
        if ($tanggalAwal != null || $tanggalAkhir != null) {
            $kelengkapan = Kelengkapan::with('analisis')->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->get(); // Mengambil data kelengkapan dari 7 hari terakhir

        } elseif ($bulan != null) {
            $kelengkapan = Kelengkapan::with('analisis')->whereMonth('created_at', '=', $bulan)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        } elseif ($tahun != null) {
            $kelengkapan = Kelengkapan::with('analisis')->whereYear('created_at', '=', $tahun)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        } elseif ($filterWaktu != null) {
            $kelengkapan = Kelengkapan::with('analisis')->whereDate('created_at', '=', $filterWaktu)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        } else {
            $kelengkapan = Kelengkapan::with('analisis')->where('created_at', '>=', $tanggalMulai)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        }
        $ruangan = User::whereHas('roles', function ($query) {
            $query->where('name', 'ruangan');
        })->get();
        foreach ($ruangan as $key => $value) {
            $array[] = $value->name;
        }
        $dataPersentaseKuantitatif = array_fill(0, count($ruangan), 0);
        foreach ($ruangan as $key => $ruanganData) {
            $ruanganID = $ruanganData->id;

            $kelengkapanRuangan = $kelengkapan->filter(function ($item) use ($ruanganID) {
                return $item->analisis->user_id === $ruanganID;
            });

            foreach ($kelengkapanRuangan as $kelengkapanItem) {

                $kelengkapanHarian = $kelengkapanRuangan->filter(function ($item) use ($kelengkapanItem) {
                    return Carbon::parse($item->created_at)->format('d M Y') === Carbon::parse($kelengkapanItem->created_at)->format('d M Y');
                });

                $jumlahKelengkapanHarian = $kelengkapanHarian->count();
                $jumlahKuantitatifHarian = $kelengkapanHarian->where('kuantitatif', true)->count();

                $persentaseKuantitatif = $jumlahKelengkapanHarian > 0 ? round(($jumlahKuantitatifHarian / $jumlahKelengkapanHarian) * 100, 2) : 0;
                $dataPersentaseKuantitatif[$key] = $persentaseKuantitatif;
            }
        }
        return $chart->barChart()
            ->setTitle('Ruangan')
            ->setSubtitle('Kelengkapan Pengisian RM Ruangan')
            ->addData('Kelengkapan', $dataPersentaseKuantitatif) // Specify the data label
            ->setXAxis($array);
    }

    public function FormulirChart($tanggalAwal, $tanggalAkhir, $filterWaktu, $bulan, $tahun)
    {
        $chart = new LarapexChartsLarapexChart();
        $array = [];

        $tanggalMulai = Carbon::now()->subDays(6);
        if ($tanggalAwal != null || $tanggalAkhir != null) {
            $kelengkapan = Kelengkapan::with('analisis', 'formulir')->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->get(); // Mengambil data kelengkapan dari 7 hari terakhir

        } elseif ($bulan != null) {
            $kelengkapan = Kelengkapan::with('analisis', 'formulir')->whereMonth('created_at', '=', $bulan)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        } elseif ($tahun != null) {
            $kelengkapan = Kelengkapan::with('analisis', 'formulir')->whereYear('created_at', '=', $tahun)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        } elseif ($filterWaktu != null) {
            $kelengkapan = Kelengkapan::with('analisis', 'formulir')->whereDate('created_at', '=', $filterWaktu)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        } else {
            $kelengkapan = Kelengkapan::with('analisis', 'formulir')->where('created_at', '>=', $tanggalMulai)->get(); // Mengambil data kelengkapan dari 7 hari terakhir
        }
        $formulir = Formulir::get();
        foreach ($formulir as $key => $value) {
            $array[] = $value->nama_formulir;
        }
        $dataPersentaseKuantitatif = array_fill(0, count($formulir), 0);
        foreach ($formulir as $key => $formulirData) {
            $formulirID = $formulirData->id;
            $kelengkapanFormulir = $kelengkapan->filter(function ($item) use ($formulirID) {
                return $item->formulir_id === $formulirID;
            });

            foreach ($kelengkapanFormulir as $kelengkapanItem) {

                $kelengkapanHarian = $kelengkapanFormulir->filter(function ($item) use ($kelengkapanItem) {
                    return Carbon::parse($item->created_at)->format('d M Y') === Carbon::parse($kelengkapanItem->created_at)->format('d M Y');
                });

                $jumlahKelengkapanHarian = $kelengkapanHarian->count();
                $jumlahKuantitatifHarian = $kelengkapanHarian->where('kuantitatif', true)->count();

                $persentaseKuantitatif = $jumlahKelengkapanHarian > 0 ? round(($jumlahKuantitatifHarian / $jumlahKelengkapanHarian) * 100, 2) : 0;
                $dataPersentaseKuantitatif[$key] = $persentaseKuantitatif;
            }
        }
        return $chart->barChart()
            ->setTitle('Formulir')
            ->setSubtitle('Kelengkapan Rekam Medis')
            ->addData('Kelengkapan', $dataPersentaseKuantitatif) // Specify the data label
            ->setXAxis($array);
    }
}
