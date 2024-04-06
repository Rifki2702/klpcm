@extends('layout.main')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="text mb-4">No RM: <strong class="text-Black">{{ $rm_pasien }}</strong></div>
                                <div class="form-group row">
                                    <label for="tglberkas" class="col-sm-2 col-form-label">Tanggal Berkas</label>
                                    <label class="col-sm-1 col-form-label text-center">:</label>
                                    <div class="col-sm-9">
                                        <input id="tglberkas" type="date" class="form-control" name="tglberkas" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="dokter" class="col-sm-2 col-form-label">Dokter</label>
                                    <label class="col-sm-1 col-form-label text-center">:</label>
                                    <div class="col-sm-9">
                                        <select id="dokter" class="form-control" name="dokter" required>
                                            <option value="">Pilih Dokter</option>
                                            @foreach($usersDokter as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                    @if (!empty($formulirs))
                                        @foreach ($formulirs as $key => $formulir)
                                            <div class="box text-white mb-3">Formulir {{ $formulir->nama_formulir }}</div>
                                            <div class="text-right">
                                                <div class="table-responsive table-striped" style="text-align: center; overflow-x: hidden;">
                                                    <table id="example" class="display expandable-table" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th class="col-6">Isi Berkas</th>
                                                                <th class="col-6">Keterangan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($formulir->isiForms as $isiForm)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $isiForm->isi }}</td>
                                                                    <td>
                                                                        <div class="row">
                                                                            <div class="col d-flex justify-content-center">
                                                                                <div class="form-check form-check-inline">
                                                                                    <label class="form-check-label" for="lengkap{{ $isiForm->id }}">
                                                                                        <input type="radio" class="form-check-input" name="keterangan[{{ $isiForm->id }}]" id="lengkap{{ $isiForm->id }}" value="lengkap" checked>
                                                                                        Lengkap
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <label class="form-check-label" for="tidaklengkap{{ $isiForm->id }}">
                                                                                        <input type="radio" class="form-check-input" name="keterangan[{{ $isiForm->id }}]" id="tidaklengkap{{ $isiForm->id }}" value="tidak lengkap">
                                                                                        Tidak Lengkap
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                    <div class="mt-4"> {{ $formulirs->links() }} </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>Tidak ada formulir yang tersedia.</p>
                                    @endif
                                    </div>
                                </div>
                                <div class="mb-4"></div> <!-- Tambahkan class mb-4 untuk memberikan margin bottom -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
