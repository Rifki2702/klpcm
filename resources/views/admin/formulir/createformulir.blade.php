@extends('layout.main')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center font-weight-bold mb-5">Tambah Formulir</h3>
                <form method="POST" action="{{ route('admin.insertformulir') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row">
                        <label for="nama_formulir" class="col-sm-2 col-form-label">Nama Formulir</label>
                        <label class="col-sm-1 col-form-label text-center">:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_formulir" name="nama_formulir" placeholder="Nama Formulir">
                            @error('nama_formulir')
                            <small>{{ $message }}</small>
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
