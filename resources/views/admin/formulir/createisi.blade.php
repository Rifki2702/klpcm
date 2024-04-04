@extends('layout.main')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <div class="col-12 text-center" style="margin-bottom: 40px;">
                    <h3>Tambah Isi Formulir "{{ $formulir->nama_formulir }}"</h3>
                </div>
                    <form method="POST" action="{{ route('admin.insertisi') }}">
                        @csrf
                        <input type="hidden" name="formulir_id" value="{{ $formulir->id }}">
                        <div class="form-group row">
                            <label for="isi" class="col-sm-2 col-form-label">Isi Formulir</label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" class="form-control" id="isi" name="isi[]" placeholder="Isi Formulir">
                                        @error('isi')
                                        <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary">Tambah</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="col-12">
                        <div class="table-responsive" style="text-align: center;">
                            <table id="example" class="table table-bordered display expandable-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Isi Formulir</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($formulir->isiForms as $index => $isiForm)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $isiForm->isi }}</td>
                                            <td>
                                                <a href="{{ route('admin.deleteisi', ['id' => $isiForm->id]) }}" class="btn btn-danger btn-sm">Hapus</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('admin.formulirmanagement') }}" class="btn btn-success">Simpan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
