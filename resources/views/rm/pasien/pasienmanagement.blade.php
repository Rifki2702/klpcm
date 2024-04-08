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
                        <a href="{{ route('admin.createpasien') }}" class="btn btn-primary mb-3"><i class=" fas fa-solid fa-user-plus"></i> Tambah User </a>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive table-striped" style="text-align: center;">
                                    <table id="example" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No RM</th>
                                                <th>Nama</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->rm }}</td>
                                                <td>{{ $d->name }}</td>
                                                <td>{{ $d->gender }}</td>
                                                <td>
                                                    <div class="action-buttons btn-toolbar d-flex justify-content-center" role="toolbar" aria-label="Toolbar with button groups">
                                                        <div class="btn-group" role="group" aria-label="First group">
                                                            <a href="{{ route('admin.editpasien',['id' => $d->id]) }}" class="btn btn-warning btn-icon-text">
                                                                <i class="ti-pencil-alt btn-icon-prepend"></i> Edit
                                                            </a>
                                                        </div>
                                                        <div class="btn-group" role="group" aria-label="Second group">
                                                            <form action="{{ route('admin.deletepasien', ['id' => $d->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ $d->name }}?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-icon-text">
                                                                    <i class="ti-trash btn-icon-prepend"></i> Hapus
                                                                </button>
                                                            </form>
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