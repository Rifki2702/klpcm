@extends('layout.main')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <button type="button" class="btn btn-primary btn-icon-text mb-3" data-toggle="modal" data-target="#tambahmodal">
            <i class="ti-pencil-alt btn-icon-prepend"></i> Analisis Baru
        </button>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="text mb-3">No RM: <strong class="text-Black">{{ $pasien->rm }}</strong></div>
                                @if($analisis->isNotEmpty())
                                <div class="row">
                                    <div class="col">
                                        <div class="table-responsive table-striped" style="text-align: center;">
                                            <table id="userTable" class="display expandable-table" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tanggal Berkas</th>
                                                        <th>Jumlah Kuantitatif</th>
                                                        <th>Jumlah Kualitatif</th>
                                                        <th>Tindakan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($analisis as $index => $data)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($data->tglberkas)->format('d F Y') }}</td>
                                                        <td>{{ number_format($hasilJumlahKuantitatif[$data->id]['persentase'], 2) }}%</td>
                                                        <td>{{ number_format($hasilJumlahKualitatif[$data->id]['persentase'], 2) }}%</td>
                                                        <td>
                                                            <div class="action-buttons btn-toolbar d-flex justify-content-center" role="toolbar" aria-label="Toolbar with button groups">
                                                                <div class="btn-group" role="group" aria-label="First group">
                                                                    <a href="{{ route('admin.hasil.pdf', ['analisis_id' => $analisis->first()->id]) }}" class="btn btn-success">
                                                                        <i class="fa fa-print"></i> Cetak
                                                                    </a>
                                                                </div>
                                                                <div class="btn-group" role="group" aria-label="Second group">
                                                                    <a href="{{ route('admin.editkuantitatif', ['analisis_id' => $data->id]) }}" class="btn btn-facebook btn-icon-text">
                                                                        <i class="ti-pencil-alt btn-icon-prepend"></i> Analisis
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-warning" role="alert">
                                    Tidak ada data analisis yang tersedia.
                                </div>
                                @endif
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