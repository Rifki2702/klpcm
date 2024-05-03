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
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal"><i class=" fas fa-solid fa-user-plus"></i>
            Tambah Data Pasien
        </button>
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
                                                            <a href="{{ route('admin.editpasien',['id' => $d->id]) }}" class="btn btn-warning btn-icon-text" data-toggle="modal" data-target="#editModal{{ $d->id }}">
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

        <!-- Modal Edit -->
        @foreach ($data as $d)
        <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $d->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{ $d->id }}">Edit Data Pasien</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('admin.updatepasien',['id' => $d->id]) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="nama_pasien" class="col-sm-2 col-form-label text-left">Nama Pasien</label>
                                <label class="col-sm-1 col-form-label text-left">:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama Pasien" value="{{ $d->name }}" required autofocus>
                                    @error('name')
                                    <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="rm" class="col-sm-2 col-form-label text-left">Nomor RM</label>
                                <label class="col-sm-1 col-form-label text-left">:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="rm" name="rm" placeholder="Nomor Rekam Medis" value="{{ $d->rm }}" required autofocus>
                                    @error('rm')
                                    <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tgl_lahir" class="col-sm-2 col-form-label text-left">Tanggal Lahir</label>
                                <label class="col-sm-1 col-form-label text-left">:</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="{{ $d->tgl_lahir }}" required autofocus>
                                    @error('tgl_lahir')
                                    <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="gender" class="col-sm-2 col-form-label text-left">Jenis Kelamin</label>
                                <label class="col-sm-1 col-form-label text-left">:</label>
                                <div class="col-sm-9">
                                    <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                        <option value="Laki-laki" {{ $d->gender ==  'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ $d->gender ==  'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-icon-text text-left" data-dismiss="modal">
                                    <i class="ti-close btn-icon-prepend"></i>
                                    Close
                                </button>
                                <button type="submit" class="btn btn-success btn-icon-text text-left">
                                    <i class="ti-save btn-icon-prepend"></i>
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Modal Tambah -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pasien</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('admin.insertpasien') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="nama_pasien" class="col-sm-2 col-form-label text-left">Nama Pasien</label>
                                <label class="col-sm-1 col-form-label text-left">:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama Pasien" value="{{ old('name') }}" required autofocus>
                                    @error('name')
                                    <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="rm" class="col-sm-2 col-form-label text-left">Nomor RM</label>
                                <label class="col-sm-1 col-form-label text-left">:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="rm" name="rm" placeholder="Nomor Rekam Medis" value="{{ old('rm') }}" required autofocus>
                                    @error('rm')
                                    <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tgl_lahir" class="col-sm-2 col-form-label text-left">Tanggal Lahir</label>
                                <label class="col-sm-1 col-form-label text-left">:</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}" required autofocus>
                                    @error('tgl_lahir')
                                    <small>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="gender" class="col-sm-2 col-form-label text-left">Jenis Kelamin</label>
                                <label class="col-sm-1 col-form-label text-left">:</label>
                                <div class="col-sm-9">
                                    <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success btn-icon-text mr-2 text-left">
                                    <i class="ti-save btn-icon-prepend"></i>
                                    Save
                                </button>
                                <button type="reset" class="btn btn-danger btn-icon-text text-left">
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
@endsection