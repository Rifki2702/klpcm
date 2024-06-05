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
                                <form id="form" action="{{ route('admin.insertkualitatif') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="analisis_id" value="{{ $analisis->id }}">
                                    <div class="form-group row">
                                        <label for="tglberkas" class="col-sm-2 col-form-label">Tanggal Berkas</label>
                                        <label class="col-sm-1 col-form-label text-center">:</label>
                                        <div class="col-sm-9">
                                            <input id="tglberkas" type="date" class="form-control" name="tanggal" value="{{ $analisis->tglberkas ?? '' }}" required disabled>
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
                                    <div class="mb-4">
                                        <button type="button" class="btn btn-success" onclick="selectAll(true)">Tepat Semua</button>
                                        <button type="button" class="btn btn-danger" onclick="selectAll(false)">Tidak Tepat Semua</button>
                                    </div>
                                    <div class="table-responsive table-striped" style="text-align: center; overflow-x: hidden;">
                                        <table class="table expandable-table mb-3" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th class="col-6">Isi Kualitatif</th>
                                                    <th class="col-6">Ketepatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($kualitatifs as $kualitatif)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $kualitatif->isi }}</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col d-flex justify-content-center">
                                                                <div class="form-check form-check-inline">
                                                                    <label class="form-check-label" for="lengkap{{ $kualitatif->id }}">
                                                                        <input type="radio" class="form-check-input" name="kualitatif[{{ $kualitatif->id }}]" id="lengkap{{ $kualitatif->id }}" value="1" {{ $kualitatif->ketepatan ? 'checked' : '' }} required>
                                                                        Tepat
                                                                    </label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <label class="form-check-label" for="tidaklengkap{{ $kualitatif->id }}">
                                                                        <input type="radio" class="form-check-input" name="kualitatif[{{ $kualitatif->id }}]" id="tidaklengkap{{ $kualitatif->id }}" value="0" {{ $kualitatif->ketepatan ? 'checked' : '' }} required>
                                                                        Tidak Tepat
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
    function selectAll(tepat) {
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        radioButtons.forEach(radio => {
            if (tepat && radio.value === "1") {
                radio.checked = true;
            } else if (!tepat && radio.value === "0") {
                radio.checked = true;
            }
        });
    }
</script>
@endsection