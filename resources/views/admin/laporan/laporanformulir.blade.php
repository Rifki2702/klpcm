@extends('layout.main')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <!-- Card for Filter -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form id="filterForm" action="{{ route('admin.laporanformulir') }}" method="GET">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Filter Waktu</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="filterWaktu" name="filter_waktu">
                                                <option value="">Pilih Filter Waktu</option>
                                                <option value="bulanan" {{ request('filter_waktu') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                                <option value="tahunan" {{ request('filter_waktu') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                                <option value="custom" {{ request('filter_waktu') == 'custom' ? 'selected' : '' }}>Custom Tanggal</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row {{ request('filter_waktu') == 'bulanan' ? '' : 'd-none' }}" id="bulanField">
                                                <label for="bulan" class="col-sm-4 col-form-label">Bulan</label>
                                                <div class="col-sm-8">
                                                    <input type="month" class="form-control" id="bulan" name="bulan" value="{{ request('bulan') }}">
                                                </div>
                                            </div>
                                            <div class="row {{ request('filter_waktu') == 'tahunan' ? '' : 'd-none' }}" id="tahunField">
                                                <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" id="tahun" name="tahun" placeholder="Masukkan Tahun" value="{{ request('tahun') }}">
                                                </div>
                                            </div>
                                            <div class="row {{ request('filter_waktu') == 'custom' ? '' : 'd-none' }}" id="customTanggalField">
                                                <label for="tanggalAwal" class="col-sm-4 col-form-label">Tanggal Awal</label>
                                                <div class="col-sm-8">
                                                    <input type="date" class="form-control" id="tanggalAwal" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
                                                </div>
                                                <label for="tanggalAkhir" class="col-sm-4 col-form-label">Tanggal Akhir</label>
                                                <div class="col-sm-8">
                                                    <input type="date" class="form-control" id="tanggalAkhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="formulir_id" class="col-sm-2 col-form-label">Pilih Formulir</label>
                                        <div class="col-sm-7">
                                            <select class="form-control" id="formulir_id" name="formulir_id">
                                                <option value="">Semua Formulir</option>
                                                @foreach ($formulirs as $formulir)
                                                <option value="{{ $formulir->id }}" {{ request('formulir_id') == $formulir->id ? 'selected' : '' }}>
                                                    {{ $formulir->nama_formulir }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="text-left mb-3">
                                    <a href="{{ route('admin.laporanformulirpdf', Request::getQueryString()) }}" class="btn btn-youtube mr-1" onclick="window.open(this.href, '_blank'); return false;">
                                        PDF <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <a href="{{ route('admin.laporanexcel', Request::getQueryString()) }}" class="btn btn-success">
                                        Excel <i class="fas fa-file-excel"></i>
                                    </a>
                                </div>
                            </div>
                            <script>
                                document.getElementById('filterWaktu').addEventListener('change', function() {
                                    var filterWaktu = this.value;
                                    document.getElementById('bulanField').classList.add('d-none');
                                    document.getElementById('tahunField').classList.add('d-none');
                                    document.getElementById('customTanggalField').classList.add('d-none');

                                    if (filterWaktu == 'bulanan') {
                                        document.getElementById('bulanField').classList.remove('d-none');
                                    } else if (filterWaktu == 'tahunan') {
                                        document.getElementById('tahunField').classList.remove('d-none');
                                    } else if (filterWaktu == 'custom') {
                                        document.getElementById('customTanggalField').classList.remove('d-none');
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($dataFormulir))
        <div class="row">
            @foreach ($dataFormulir as $formulir)
            <div class="col-md-12 grid-margin stretch-card">
                <!-- Card for Each Form -->
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $formulir['nama_formulir'] }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive table-striped text-center mt-4">
                                    <table class="table display expandable-table">
                                        <thead>
                                            <tr>
                                                <th>Isi Formulir</th>
                                                <th>Persentase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($formulir['isi_formulir'] as $isi)
                                            <tr>
                                                <td>{{ $isi['isi'] }}</td>
                                                <td>{{ $isi['persentase_lengkap'] }}%</td>
                                            </tr>
                                            @endforeach
                                            @if (empty($formulir['isi_formulir']))
                                            <tr>
                                                <td colspan="2">Tidak ada data yang tersedia</td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td><strong>Total Data:</strong> {{ count($formulir['isi_formulir']) }}</td>
                                                <td><strong>Rata-rata Persentase:</strong> {{ number_format(collect($formulir['isi_formulir'])->avg('persentase_lengkap'), 2) }}%</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection