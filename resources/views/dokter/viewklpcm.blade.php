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
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Kelengkapan Analisis</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <!-- Isi konten modal disini -->
                                                                    <p>No RM:</p>
                                                                    <p>Nama: </p>
                                                                    <p>Tgl Berkas: </p>
                                                                    <p>Kuantitatif: %</p>
                                                                    <!-- Tambahkan informasi lain yang diperlukan -->
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                    <!-- Tambahkan tombol atau form untuk aksi lain jika diperlukan -->
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