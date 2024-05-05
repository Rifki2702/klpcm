@extends('layout.main')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive table-striped" style="text-align: center;">
                                    <table id="userTable" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No RM</th>
                                                <th>Nama</th>
                                                <th>Tgl Berkas</th>
                                                <th>Kuantitatif</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($analisisKurangDariSeratus as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->pasien->rm }}</td>
                                                <td>{{ $item->pasien->name }}</td>
                                                <td>{{ $item->tglberkas }}</td>
                                                <td>{{ $item->persentaseKuantitatif }}%</td>
                                                <td>
                                                    <button type="button" class="btn btn-facebook btn-icon-text" data-toggle="modal" data-target="#kelengkapanModal_{{ $loop->iteration }}">
                                                        <i class="ti-eye btn-icon-prepend"></i> Kelengkapan
                                                    </button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="kelengkapanModal_{{ $loop->iteration }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document"> <!-- Ubah ukuran modal menjadi modal-lg -->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Kelengkapan Analisis</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row justify-content-center">
                                                                        @php
                                                                        $formulirGroups = $item->kelengkapanTidakLengkap->groupBy('nama_formulir');
                                                                        @endphp
                                                                        @foreach ($formulirGroups as $namaFormulir => $kelengkapans)
                                                                        <div class="col-md-6 mb-4"> <!-- Ubah ukuran kolom menjadi col-md-4 untuk menyesuaikan modal besar -->
                                                                            <div class="card border">
                                                                                <div class="card-header text-center">
                                                                                    <h5><strong>{{ $namaFormulir ?? 'Formulir Tidak Ditemukan' }}</strong></h5>
                                                                                </div>
                                                                                <div class="card-body text-center">
                                                                                    @foreach ($kelengkapans as $kelengkapan)
                                                                                    <p>{{ $kelengkapan['isi'] ?? 'Isi Formulir Tidak Ditemukan' }}</p>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @if ($loop->iteration % 2 == 0) <!-- Sesuaikan dengan jumlah kolom baru -->
                                                                    </div>
                                                                    <div class="row justify-content-center">
                                                                        @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection