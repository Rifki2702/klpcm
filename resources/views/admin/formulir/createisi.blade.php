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
        <div class="card">
            <div class="card-body">
                <div class="col-12 text-center" style="margin-bottom: 20px;">
                    <h3><strong> Isi Formulir "{{ $formulir->nama_formulir }}" </strong></h3>
                </div>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahIsiModal"><i class="fas fa-plus btn-icon-prepend"></i> Tambah Isi Formulir</button>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editNamaFormulirModal"><i class="fas fa-pencil-alt btn-icon-prepend"></i> Edit Nama Formulir</button>

                <!-- Modal Tambah Isi -->
                <div class="modal fade" id="tambahIsiModal" tabindex="-1" role="dialog" aria-labelledby="tambahIsiModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="tambahIsiModalLabel">Tambah Isi Formulir</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.insertisi') }}">
                                    @csrf
                                    <input type="hidden" name="formulir_id" value="{{ $formulir->id }}">
                                    <div class="form-group row">
                                        <label for="isi" class="col-sm-4 col-form-label text-left">Isi Formulir</label>
                                        <label class="col-sm-1 col-form-label text-left">:</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="isi" name="isi[]" placeholder="Isi Formulir">
                                        </div>
                                        @error('isi')
                                        <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times btn-icon-prepend"></i> Batal</button>
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save btn-icon-prepend"></i> Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-12">
                    <div class="table-responsive table-striped" style="text-align: center;">
                        <table id="userTable" class="display expandable-table" style="width:100%">
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
                                        <!-- Trigger modal untuk edit -->
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $isiForm->id }}"><i class="fas fa-pencil-alt btn-icon-prepend"></i> Edit</button>

                                        <!-- Modal Edit -->
                                        <div class="modal fade" id="editModal{{ $isiForm->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $isiForm->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel{{ $isiForm->id }}">Edit Isi Formulir</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('admin.updateisi', ['id' => $isiForm->id]) }}" method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="form-group row">
                                                                <label for="isi" class="col-sm-4 col-form-label text-left">Isi Formulir</label>
                                                                <label class="col-sm-1 col-form-label text-left">:</label>
                                                                <div class="col-sm-7">
                                                                    <input type="text" class="form-control" id="isi" name="isi" value="{{ $isiForm->isi }}">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-icon-text" data-dismiss="modal">
                                                                    <i class="fas fa-times btn-icon-prepend"></i>
                                                                    Batal
                                                                </button>
                                                                <button type="submit" class="btn btn-success btn-icon-text">
                                                                    <i class="fas fa-save btn-icon-prepend"></i>
                                                                    Simpan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Trigger modal untuk konfirmasi hapus -->
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $isiForm->id }}"><i class="fas fa-trash btn-icon-prepend"></i> Hapus</button>

                                        <!-- Modal Hapus -->
                                        <div class="modal fade" id="deleteModal{{ $isiForm->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $isiForm->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $isiForm->id }}">Konfirmasi Hapus</h5>
                                                        <button type="button" the="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus <strong> {{ $isiForm->isi }} </strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" the="btn btn-secondary btn-icon-text" data-dismiss="modal">
                                                            <i class="fas fa-times btn-icon-prepend"></i>
                                                            Batal
                                                        </button>
                                                        <a href="{{ route('admin.deleteisi', ['id' => $isiForm->id]) }}" class="btn btn-danger btn-icon-text">
                                                            <i class="fas fa-trash btn-icon-prepend"></i>
                                                            Hapus
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Edit Nama Formulir -->
                                        <div class="modal fade" id="editNamaFormulirModal" tabindex="-1" role="dialog" aria-labelledby="editNamaFormulirModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editNamaFormulirModalLabel">Edit Nama Formulir</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="{{ route('admin.updateformulir', $formulir->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="form-group row">
                                                                <label for="nama_formulir" class=" col-sm-4 col-form-label text-left">Nama Formulir</label>
                                                                <label class="col-sm-1 col-form-label text-left">:</label>
                                                                <div class="col-sm-7">
                                                                    <input type="text" class="form-control" id="nama_formulir" name="nama_formulir" value="{{ $formulir->nama_formulir }}" required>
                                                                </div>
                                                                @error('isi')
                                                                <small>{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times btn-icon-prepend"></i> Batal</button>
                                                                <button type="submit" class="btn btn-warning"><i class="fas fa-save btn-icon-prepend"></i>Simpan</button>
                                                            </div>
                                                        </form>
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
                <div class="col-4">
                    <a href="{{ route('admin.formulirmanagement') }}" class="btn btn-success">Simpan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection