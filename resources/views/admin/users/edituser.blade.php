@extends('layout.main')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center font-weight-bold mb-5">Edit Data Petugas</h3>
                <form method="POST" action="{{ route('admin.updateuser',['id' => $data->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group row">
                        <label for="nama_petugas" class="col-sm-2 col-form-label">Nama Petugas</label>
                        <label class="col-sm-1 col-form-label text-center">:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="{{ $data->name }}" placeholder="Nama Petugas">
                            @error('name')
                            <small>{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <label class="col-sm-1 col-form-label text-center">:</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" name="email" value="{{ $data->email }}" placeholder="Email">
                            @error('email')
                            <small>{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="role_id" class="col-sm-2 col-form-label">Role</label>
                        <label class="col-sm-1 col-form-label text-center">:</label>
                        <div class="col-sm-9">
                            <select name="role_id" id="role_id" class="form-control">
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $data->roles->contains('id', $role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                            <small>{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="gender" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                        <label class="col-sm-1 col-form-label text-center">:</label>
                        <div class="col-sm-9">
                            <select name="gender" id="gender" class="form-control">
                                <option value="Laki-Laki" {{ $data->gender == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="Perempuan" {{ $data->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="foto" class="col-sm-2 col-form-label">Foto</label>
                        <label class="col-sm-1 col-form-label text-center">:</label>
                        <div class="col-sm-9">
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

                    <div class="form-group row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
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
@endsection