@extends('layout.main')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if(session('danger'))
        <div class="alert alert-danger">
            {{ session('danger') }}
        </div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
        @endif
        <div class="d-flex justify-content mb-3">
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahDokter"><i class="fas fa-user-plus"></i> Tambah Dokter</a>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-striped" style="text-align: center;">
                            <table id="userTable" class="display expandable-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dokter</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dokter as $dok)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $dok->nama_dokter }}</td>
                                        <td>
                                            <button class="btn btn-warning" data-toggle="modal" data-target="#modalEditDokter{{ $dok->id }}"><i class="fas fa-edit"></i> Edit</button>
                                            <button class="btn btn-danger" data-toggle="modal" data-target="#modalHapusDokter{{ $dok->id }}"><i class="fas fa-trash-alt"></i> Hapus</button>
                                        </td>
                                    </tr>
                                    <!-- Modal Edit Dokter -->
                                    <div class="modal fade" id="modalEditDokter{{ $dok->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditDokterLabel{{ $dok->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditDokterLabel{{ $dok->id }}">Edit Dokter</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.editdokter', ['id' => $dok->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group row">
                                                            <label for="nama_dokter" class="col-sm-4 col-form-label">Nama Dokter</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" name="nama_dokter" value="{{ old('nama_dokter', $dok->nama_dokter) }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update Dokter</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal Hapus Dokter -->
                                    <div class="modal fade" id="modalHapusDokter{{ $dok->id }}" tabindex="-1" role="dialog" aria-labelledby="modalHapusDokterLabel{{ $dok->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalHapusDokterLabel{{ $dok->id }}">Hapus Dokter</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus dokter ini?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.deletedokter', ['id' => $dok->id]) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
<!-- Modal Tambah Dokter -->
<div class="modal fade" id="modalTambahDokter" tabindex="-1" role="dialog" aria-labelledby="modalTambahDokterLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahDokterLabel">Tambah Dokter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.insertdokter') }}" method="post">
                    @csrf
                    <div class="form-group row">
                        <label for="namaDokter" class="col-sm-4 col-form-label">Nama Dokter</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" placeholder="Masukkan nama dokter" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection