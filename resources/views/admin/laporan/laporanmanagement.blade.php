@extends('layout.main')
@push('js')
<<<<<<< HEAD
<script>
    $(document).ready(function() {
        $('#filterWaktu').change(function() {
            var id = $(this).val();
            console.log(id);
            if (id != '0') {
                $('#bulanField').addClass('d-none');
                $('#tahunField').addClass('d-none');
                $('#customTanggalField').addClass('d-none');

                if (id == 'bulanan') {
                    $('#bulanField').removeClass('d-none');
                } else if (id == 'tahunan') {
                    $('#tahunField').removeClass('d-none');
                } else if (id == 'custom') {
                    $('#customTanggalField').removeClass('d-none');
                }
            } else {
                $('#bulanField').addClass('d-none');
                $('#tahunField').addClass('d-none');
                $('#customTanggalField').addClass('d-none');
            }
        });
    });
</script>
=======
    <script>
        $(document).ready(function() {
            $('#filterWaktu').change(function() {
                var id = $(this).val();
                console.log(id);
                if (id != '0') {
                    if (id == 'bulanan') {
                        $('#bulanField').removeClass('d-none');
                        $('#customTanggalField').addClass('d-none');
                        $('#tahunField').addClass('d-none');
                    }else if(id == 'tahunan') {
                        $('#bulanField').addClass('d-none');
                        $('#customTanggalField').addClass('d-none');
                        $('#tahunField').removeClass('d-none');

                    }else if(id == 'custom') {
                        $('#bulanField').addClass('d-none');
                        $('#tahunField').addClass('d-none');
                        $('#customTanggalField').removeClass('d-none');
                    }
                } else {
                    $('#bulanField').addClass('d-none');
                    $('#tahunField').addClass('d-none');
                    $('#customTanggalField').addClass('d-none');

                }
            })

        })
    </script>
>>>>>>> 36eecf08a2c7955fec53765269f7437cf8212087
@endpush
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form id="filterForm" action="{{ route('admin.laporanfilter') }}" method="GET">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Filter Waktu</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="filterWaktu" name="filter_waktu">
                                                <option value="0" {{ request('filter_waktu') == '0' ? 'selected' : '' }}>Pilih Filter Waktu</option>
                                                <option value="bulanan" {{ request('filter_waktu') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                                <option value="tahunan" {{ request('filter_waktu') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                                <option value="custom" {{ request('filter_waktu') == 'custom' ? 'selected' : '' }}>Custom Tanggal</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row d-none" id="bulanField">
                                                <label for="bulan" class="col-sm-4 col-form-label">Bulan</label>
                                                <div class="col-sm-8">
                                                    <input type="month" class="form-control" id="bulan" name="bulan" value="{{ request('bulan') }}">
                                                </div>
                                            </div>
                                            <div class="row d-none" id="tahunField">
                                                <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" id="tahun" name="tahun" placeholder="Masukkan Tahun" value="{{ request('tahun') }}">
                                                </div>
                                            </div>
                                            <div class="row d-none" id="customTanggalField">
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
                                        <label class="col-sm-2 col-form-label">Filter Ruangan</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="filterRuangan" name="filter_ruangan">
                                                <option value="">Semua Ruangan</option>
                                                @foreach (\App\Models\User::whereHas('roles', function ($query) {
                                                $query->where('name', 'ruangan');
                                                })->get() as $ruangan)
                                                <option value="{{ $ruangan->id }}" {{ request('filter_ruangan') == $ruangan->id ? 'selected' : '' }}>{{ $ruangan->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="col-sm-2 col-form-label">Filter Status</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="filterStatus" name="filter_status">
                                                <option value="">Semua Status</option>
                                                <option value="selesai" {{ request('filter_status') == 'selesai' ? 'selected' : '' }}>Complete</option>
                                                <option value="proses" {{ request('filter_status') == 'proses' ? 'selected' : '' }}>IMR</option>
                                                <option value="tertunda" {{ request('filter_status') == 'tertunda' ? 'selected' : '' }}>DMR</option>
                                            </select>
                                        </div>
                                    </div>
<<<<<<< HEAD
                                    <div class="mb-4 text-right">
                                        <button class="btn btn-primary">Filter</button>
=======
                                    <div class="mb-4" style="@auth
                                         display: flex;
                                         justify-items: end;
                                    @endauth">
                                        <button class="btn btn-primary">Filter</button>
                                        <hr>
>>>>>>> 36eecf08a2c7955fec53765269f7437cf8212087
                                    </div>
                                </form>
                                <div class="text-left mb-3">
                                    <a href="{{ route('admin.laporanpdf', Request::getQueryString()) }}" class="btn btn-youtube mr-1" target="_blank">
                                        PDF <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <a href="{{ route('admin.laporanexcel', Request::getQueryString()) }}" class="btn btn-success">
                                        Excel <i class="fas fa-file-excel"></i>
                                    </a>
                                </div>
                            </div>
                            {{-- <script>
                                document.querySelectorAll('select, input').forEach(item => {
                                    item.addEventListener('change', event => {
                                        document.getElementById('filterForm').submit();
                                    });
                                });
                            </script> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive text-center">
                                    <table id="userTable" class="table display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No RM</th>
                                                <th>Nama</th>
                                                <th>Tgl Berkas</th>
                                                <th>Tgl Analisis</th>
                                                <th>Kuantitatif (%)</th>
                                                <th>Kualitatif (%)</th>
                                                <th>Ruangan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dataAnalisis as $data)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $data['analisis']->pasien->rm }}</td>
                                                <td>{{ $data['analisis']->pasien->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($data['analisis']->tglberkas)->format('d/m/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($data['analisis']->tglcek)->format('d/m/Y') }}</td>
                                                <td>{{ $data['persentase']['kuantitatif'] }}%</td>
                                                <td>{{ $data['persentase']['kualitatif'] }}%</td>
                                                <td>{{ $data['analisis']->user?->name }}</td>
                                                <td>
                                                    @if($data['status'] == 'complete')
                                                    <span class="badge badge-success">Complete</span>
                                                    @elseif($data['status'] == 'imr')
                                                    <span class="badge badge-warning">IMR</span>
                                                    @elseif($data['status'] == 'dmr')
                                                    <span class="badge badge-danger">DMR</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
