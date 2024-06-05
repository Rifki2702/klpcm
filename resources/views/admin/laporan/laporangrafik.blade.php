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
                                <form id="filterForm" action="{{ route('admin.laporangrafik') }}" method="GET">
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
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="text-left mb-3">
                                    <a href="{{ route('admin.laporangrafikPDF',request()->all()) }}" class="btn btn-youtube mr-1" target="_blank">
                                        PDF <i class="fas fa-file-pdf"></i>
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
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h4 class="fw-bold"> Filter yang Dipilih : {{ ucwords($filter_waktu) }} - Periode :
                            @if ($filter_waktu == 'bulanan')
                            {{ $bulan }}
                            @elseif ($filter_waktu == 'tahunan')
                            {{ $tahun }}
                            @else
                            {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">
                        <div id="LengkapTepatChartContainer">
                            {!! $LengkapTepatChart->container() !!}
                        </div>
                        <div id="DokterChartContainer">
                            {!! $DokterChart->container() !!}
                        </div>
                        <div id="RuanganChartContainer">
                            {!! $RuanganChart->container() !!}
                        </div>
                        <div id="FormulirChartContainer">
                            {!! $FormulirChart->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ $LengkapTepatChart->cdn() }}"></script>
{{ $LengkapTepatChart->script() }}
<script src="{{ $DokterChart->cdn() }}"></script>
{{ $DokterChart->script() }}
<script src="{{ $RuanganChart->cdn() }}"></script>
{{ $RuanganChart->script() }}
<script src="{{ $FormulirChart->cdn() }}"></script>
{{ $FormulirChart->script() }}
@endsection