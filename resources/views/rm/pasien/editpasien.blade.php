@extends('layout.main')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center font-weight-bold mb-5">Edit Data Pasien</h3>
                <form method="POST" action="{{ route('admin.updatepasien',['id' => $data->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group row">
                        <label for="nama_petugas" class="col-sm-2 col-form-label">Nama Pasien</label>
                        <label class="col-sm-1 col-form-label text-center">:</label>
                        <div class="col-sm-9">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $data->name }}" required autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">No RM</label>
                        <label class="col-sm-1 col-form-label text-center">:</label>
                        <div class="col-sm-9">
                            <input id="rm" type="text" class="form-control @error('rm') is-invalid @enderror" name="rm" value="{{ $data->rm }}" required>
                            @error('rm')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                        <label class="col-sm-1 col-form-label text-center">:</label>
                        <div class="col-sm-9">
                            <input id="tgl_lahir" type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" name="tgl_lahir" value="{{ $data->tgl_lahir }}" required>
                            @error('tgl_lahir')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                        <label class="col-sm-1 col-form-label text-center">:</label>
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