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
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahUser"><i class="fas fa-user-plus"></i> Tambah User</a>
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
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Email</th>
                                        <th>Foto</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->name }}</td>
                                        <td>
                                            @foreach($d->roles as $role)
                                            {{ $role->name }}
                                            @endforeach
                                        </td>
                                        <td>{{ $d->email }}</td>
                                        <td><img src="{{ asset('storage/foto-user/'.$d->image) }}" alt="" width="100"></td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="First group">
                                                <button type="button" class="btn btn-warning btn-icon-text" data-toggle="modal" data-target="#modalEditUser{{ $d->id }}">
                                                    <i class="ti-pencil-alt btn-icon-prepend"></i> Edit
                                                </button>
                                            </div>
                                            <div class="btn-group" role="group" aria-label="Second group">
                                                <button type="button" class="btn btn-danger btn-icon-text" data-toggle="modal" data-target="#modalDeleteUser{{ $d->id }}">
                                                    <i class="ti-trash btn-icon-prepend"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Modal Tambah User -->
                                    <div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalTambahUserLabel">Tambah User</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{ route('admin.insertuser') }}" enctype="multipart/form-data">
                                                        @csrf

                                                        <div class="form-group row">
                                                            <label for="name" class="col-sm-2 col-form-label text-left">Nama Petugas</label>
                                                            <label class="col-sm-1 col-form-label">:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" id="name" name="name" placeholder="Nama Petugas" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="email" class="col-sm-2 col-form-label text-left">Email</label>
                                                            <label class="col-sm-1 col-form-label">:</label>
                                                            <div class="col-sm-9">
                                                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="role_id" class="col-sm-2 col-form-label text-left">Role</label>
                                                            <label class="col-sm-1 col-form-label">:</label>
                                                            <div class="col-sm-9">
                                                                <select name="role_id" id="role_id" class="form-control" required>
                                                                    @foreach($roles as $role)
                                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="password" class="col-sm-2 col-form-label text-left">Password</label>
                                                            <label class="col-sm-1 col-form-label">:</label>
                                                            <div class="col-sm-9">
                                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="password_confirmation" class="col-sm-2 col-form-label text-left">Konfirmasi Password</label>
                                                            <label class="col-sm-1 col-form-label">:</label>
                                                            <div class="col-sm-9">
                                                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="gender" class="col-sm-2 col-form-label text-left">Jenis Kelamin</label>
                                                            <label class="col-sm-1 col-form-label">:</label>
                                                            <div class="col-sm-9">
                                                                <select name="gender" id="gender" class="form-control" required>
                                                                    <option value="Laki-Laki">Laki-Laki</option>
                                                                    <option value="Perempuan">Perempuan</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="foto" class="col-sm-2 col-form-label text-left">Foto</label>
                                                            <label class="col-sm-1 col-form-label text-left">:</label>
                                                            <div class="col-sm-9 text-left">
                                                                <input type="file" name="foto" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp" style="display: none;" onchange="displaySelectedPhoto(this)">
                                                                <button type="button" class="file-upload-browse btn btn-outline-secondary btn-icon-text" onclick="document.getElementById('exampleInputFile').click();">
                                                                    <i class="ti-image btn-icon-prepend"></i>
                                                                    Upload
                                                                </button>
                                                                <div class="mt-2">
                                                                    <img id="selectedPhoto" src="#" alt="Selected Photo" style="max-width: 100%; max-height: 200px; display: none;">
                                                                    <button type="button" class="btn btn-danger btn-sm mt-2" id="deleteButton" onclick="resetSelectedPhoto()" style="display: none;">Hapus</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success btn-icon-text">
                                                                <i class="ti-save btn-icon-prepend"></i>
                                                                Simpan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal Tambah User -->

                                    <!-- Modal Edit User -->
                                    <div class="modal fade" id="modalEditUser{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditUserLabel{{ $d->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditUserLabel{{ $d->id }}">Edit Data Petugas</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{ route('admin.updateuser',['id' => $d->id]) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="form-group row">
                                                            <label for="name" class="col-sm-2 col-form-label text-left">Nama Petugas</label>
                                                            <label class="col-sm-1 col-form-label text-left">:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" id="name" name="name" value="{{ $d->name }}" placeholder="Nama Petugas">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="email" class="col-sm-2 col-form-label text-left">Email</label>
                                                            <label class="col-sm-1 col-form-label text-left">:</label>
                                                            <div class="col-sm-9">
                                                                <input type="email" class="form-control" id="email" name="email" value="{{ $d->email }}" placeholder="Email">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="role_id" class="col-sm-2 col-form-label text-left">Role</label>
                                                            <label class="col-sm-1 col-form-label text-left">:</label>
                                                            <div class="col-sm-9">
                                                                <select name="role_id" id="role_id" class="form-control">
                                                                    @foreach($roles as $role)
                                                                    <option value="{{ $role->id }}" {{ $d->roles->contains('id', $role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="gender" class="col-sm-2 col-form-label text-left">Jenis Kelamin</label>
                                                            <label class="col-sm-1 col-form-label text-left">:</label>
                                                            <div class="col-sm-9">
                                                                <select name="gender" id="gender" class="form-control">
                                                                    <option value="Laki-Laki" {{ $d->gender == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                                                    <option value="Perempuan" {{ $d->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="foto" class="col-sm-2 col-form-label text-left">Foto</label>
                                                            <label class="col-sm-1 col-form-label text-left">:</label>
                                                            <div class="col-sm-9 text-left">
                                                                <input type="file" name="foto" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp" style="display: none;" onchange="displaySelectedPhoto(this)">
                                                                <button type="button" class="file-upload-browse btn btn-outline-secondary btn-icon-text" onclick="document.getElementById('exampleInputFile').click();">
                                                                    <i class="ti-image btn-icon-prepend"></i>
                                                                    Upload
                                                                </button>
                                                                <div class="mt-2">
                                                                    <img id="selectedPhoto" src="#" alt="Selected Photo" style="max-width: 100%; max-height: 200px; display: none;">
                                                                    <button type="button" class="btn btn-danger btn-sm mt-2" id="deleteButton" onclick="resetSelectedPhoto()" style="display: none;">Hapus</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <div class="col-sm-12 text-right">
                                                                <button type="submit" class="btn btn-success btn-icon-text">
                                                                    <i class="ti-save btn-icon-prepend"></i>
                                                                    Save
                                                                </button>
                                                                <button type="reset" class="btn btn-danger btn-icon-text">
                                                                    <i class="ti-reload btn-icon-prepend"></i>
                                                                    Reset
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal Edit User -->

                                    <!-- Modal Delete User -->
                                    <div class="modal fade" id="modalDeleteUser{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDeleteUserLabel{{ $d->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalDeleteUserLabel{{ $d->id }}">Hapus User</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus user {{ $d->name }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.deleteuser', ['id' => $d->id]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal Delete User -->
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