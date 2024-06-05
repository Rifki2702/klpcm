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
                                                                    <a href="{{ route('admin.hasil.pdf', ['analisis_id' => $data->id]) }}" class="btn btn-success">
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
        <!-- Modal -->
        <div class="modal fade" id="tambahmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Analisis Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.insertawal') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <label for="tglberkas" class="col-sm-4 col-form-label">Tanggal Berkas</label>
                                <div class="col-sm-1 col-form-label text-center">:</div>
                                <div class="col-sm-7">
                                    <input id="tglberkas" type="date" class="form-control" name="tanggal" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ruangan" class="col-sm-4 col-form-label">Ruangan</label>
                                <div class="col-sm-1 col-form-label text-center">:</div>
                                <div class="col-sm-7">
                                    <select id="ruangan" class="form-control" name="ruangan" required>
                                        <option value="">Pilih Ruangan</option>
                                        @foreach($usersRuangan as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dokter_id" class="col-sm-4 col-form-label">Dokter</label>
                                <div class="col-sm-1 col-form-label text-center">:</div>
                                <div class="col-sm-7">
                                    <select id="dokter_id" class="form-control" name="dokter_id" required>
                                        <option value="">Pilih Dokter</option>
                                        @foreach($dokter as $dok)
                                        <option value="{{ $dok->id }}">{{ $dok->nama_dokter }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                    </div>
                    <!-- Hidden input for pasien_id -->
                    <input type="hidden" name="pasien_id" value="{{ $pasien->id }}">
                    <!-- Input for tglcek will be filled with current time when saving -->
                    <input type="hidden" name="tglcek" value="{{ now()->format('Y-m-d H:i:s') }}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection