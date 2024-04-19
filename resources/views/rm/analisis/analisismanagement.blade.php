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
                                                            <a href="{{ route('admin.analisislama', ['id' => $id_pasien]) }})" class="btn btn-warning btn-icon-text">
                                                                <i class="ti-pencil-alt btn-icon-prepend"></i> Analisis Lama
                                                            </a>
                                                        </div>
                                                        <div class="btn-group" role="group" aria-label="Second group">
                                                            <button type="button" class="btn btn-success btn-icon-text" data-toggle="modal" data-target="#myModal">
                                                                <i class="ti-pencil-alt btn-icon-prepend"></i> Analisis Baru
                                                            </button>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- Modal -->
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                            <label class="col-sm-1 col-form-label text-center">:</label>
                                                            <div class="col-sm-7">
                                                                <input id="tglberkas" type="date" class="form-control" name="tanggal" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="dokter" class="col-sm-4 col-form-label">Dokter</label>
                                                            <label class="col-sm-1 col-form-label text-center">:</label>
                                                            <div class="col-sm-7">
                                                                <select id="dokter" class="form-control" name="dokter" required>
                                                                    <option value="">Pilih Dokter</option>
                                                                    @foreach($usersDokter as $user)
                                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <!-- Hidden input for pasien_id -->
                                                        <input type="hidden" name="pasien_id" value="{{ $id_pasien }}">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection