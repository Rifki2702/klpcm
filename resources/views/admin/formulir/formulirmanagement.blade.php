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

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.createformulir') }}" class="btn btn-primary mb-3"><i class="fas fa-solid fa-file-signature"></i> Tambah Form </a>
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive table-striped" style="text-align: center;">
                                    <table id="example" class="display expandable-table" style="width:100%">
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

                                                    <!-- Modal -->
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