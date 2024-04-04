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
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive table-striped" style="text-align: center;">
                                    <table id="example" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No RM</th>
                                                <th>Nama</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->rm }}</td>
                                                <td>{{ $d->name }}</td>
                                                <td>
                                                    <div class="action-buttons btn-toolbar d-flex justify-content-center" role="toolbar" aria-label="Toolbar with button groups">
                                                        @foreach($data as $pasien)
                                                        @php $id_pasien = $pasien->id; @endphp
                                                            <div class="btn-group" role="group" aria-label="First group">
                                                                <a href="{{ route('admin.analisislama', ['id' => $id_pasien]) }}) }}" class="btn btn-warning btn-icon-text">
                                                                    <i class="ti-pencil-alt btn-icon-prepend"></i> Analisis Lama
                                                                </a>
                                                            </div>
                                                            <div class="btn-group" role="group" aria-label="Second group">
                                                                <a href="{{ route('admin.analisisbaru', ['id' => $id_pasien]) }}" class="btn btn-success btn-icon-text">
                                                                    <i class="ti-pencil-alt btn-icon-prepend"></i> Analisis Baru
                                                                </a>
                                                            </div>
                                                        @endforeach
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
