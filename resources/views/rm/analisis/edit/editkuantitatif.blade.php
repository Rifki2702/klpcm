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
                                <form id="form" action="{{ route('admin.updateform', ['id' => $analisis->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="analisis_id" value="{{ $analisis->id }}">
                                    <div class="form-group row">
                                        <label for="tglberkas" class="col-sm-2 col-form-label">Tanggal Berkas</label>
                                        <label class="col-sm-1 col-form-label text-center">:</label>
                                        <div class="col-sm-9">
                                            <input id="tglberkas" type="date" class="form-control" name="tanggal" value="{{ $analisis->tglberkas ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="dokter" class="col-sm-2 col-form-label">Dokter</label>
                                        <label class="col-sm-1 col-form-label text-center">:</label>
                                        <div class="col-sm-9">
                                            <select id="dokter" class="form-control" name="dokter" required disabled>
                                                <option value="">Pilih Dokter</option>
                                                @foreach($usersRuangan as $user)
                                                <option value="{{ $user->id }}" {{ isset($analisis) && $analisis->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @foreach($formulirs as $formulir)
                                    <div class="card mb-2" style="border: 1px solid #ccc;">
                                        <div class="card-header">
                                            Formulir {{ $formulir->nama_formulir }}
                                            <button type="button" onclick="selectAll('lengkap', {{ $formulir->id }});" class="btn btn-sm btn-success">Pilih Semua Lengkap</button>
                                            <button type="button" onclick="selectAll('tidaklengkap', {{ $formulir->id }});" class="btn btn-sm btn-danger">Pilih Semua Tidak Lengkap</button>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive table-striped" style="text-align: center; overflow-x: hidden;">
                                                <table id="example" class="display expandable-table mb-3" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th class="col-6">Isi Berkas</th>
                                                            <th class="col-6">Kuantitatif</th>
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
                                                                                <input type="radio" class="form-check-input" name="kuantitatif[{{ $isiForm->id }}]" id="lengkap{{ $isiForm->id }}" value="1" {{ $isiForm->kelengkapan && $isiForm->kelengkapan->kuantitatif == '1' ? 'checked' : '' }} required>
                                                                                Lengkap
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <label class="form-check-label" for="tidaklengkap{{ $isiForm->id }}">
                                                                                <input type="radio" class="form-check-input" name="kuantitatif[{{ $isiForm->id }}]" id="tidaklengkap{{ $isiForm->id }}" value="0" {{ $isiForm->kelengkapan && $isiForm->kelengkapan->kuantitatif == '0' ? 'checked' : '' }} required>
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
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="mb-4"></div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
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

<script>
    function selectAll(type, formulirId) {
        var radios = document.querySelectorAll('input[name^="kuantitatif"]');
        radios.forEach(function(radio) {
            if (radio.id.startsWith(type) && radio.closest('.card').querySelector('button').onclick.toString().includes(formulirId)) {
                radio.checked = true;
            }
        });
    }
</script>
@endsection