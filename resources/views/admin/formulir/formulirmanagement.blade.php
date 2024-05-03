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
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahFormulir"><i class="fas fa-solid fa-file-signature"></i>
            Tambah Formulir
        </button>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive table-striped" style="text-align: center;">
                                    <table id="userTable" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>ID</th>
                                                <th>Nama Formulir</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->id }}</td>
                                                <td>{{ $d->nama_formulir }}</td>
                                                <td>
                                                    <!-- Button trigger modal -->
                                                    <div class="action-buttons btn-toolbar d-flex justify-content-center" role="toolbar" aria-label="Toolbar with button groups">
                                                        <div class="btn-group" role="group" aria-label="First group">
                                                            <button type="button" class="btn btn-success btn-icon-text" data-toggle="modal" data-target="#viewModal{{ $d->id }}">
                                                                <i class="ti-eye btn-icon-prepend"></i> View
                                                            </button>
                                                        </div>
                                                        <div class="btn-group" role="group" aria-label="Second group">
                                                            <a href="{{ route('admin.createisi', ['id' => $d->id]) }}" class="btn btn-warning btn-icon-text">
                                                                <i class="ti-pencil-alt btn-icon-prepend"></i> Edit
                                                            </a>
                                                        </div>
                                                        <div class="btn-group" role="group" aria-label="Third group">
                                                            <form action="{{ route('admin.deleteformulir', ['id' => $d->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus formulir ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-icon-text">
                                                                    <i class="ti-trash btn-icon-prepend"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- Modal view -->
                                                    <div class="modal fade" id="viewModal{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">View Formulir</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>{{ $d->nama_formulir }}</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($d->isiForms as $isi)
                                                                            <tr>
                                                                                <td>{{ $isi->isi }}</td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- Modal tambah form-->
                                    <div class="modal fade" id="modalTambahFormulir" tabindex="-1" role="dialog" aria-labelledby="modalTambahFormulirLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalTambahFormulirLabel">Tambah Formulir</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.insertformulir') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-group row">
                                                            <label for="nama_formulir" class="col-sm-4 col-form-label text-left">Nama Formulir</label>
                                                            <label class="col-sm-1 col-form-label text-left">:</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" class="form-control" id="nama_formulir" name="nama_formulir" placeholder="Nama Formulir">
                                                                @error('nama_formulir')
                                                                <small>{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success btn-icon-text">
                                                                <i class="ti-save btn-icon-prepend"></i>
                                                                Simpan
                                                            </button>
                                                            <button type="reset" class="btn btn-danger btn-icon-text">
                                                                <i class="ti-reload btn-icon-prepend"></i>
                                                                Reset
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <!-- partial -->
</div>
@endsection