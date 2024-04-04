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
                                            <option value="">dr. Maulana</option>
                                            <option value="">dr. Rifki</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col">
                                    <div class="box text-white mb-3">CPPT</div>
                                    <div class="table-responsive table-striped" style="text-align: center;">
                                        <table id="example" class="display expandable-table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th class="col-6">Isi Berkas</th>
                                                    <th class="col-6">Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Nama</td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col d-flex justify-content-center">
                                                                <div class="form-check form-check-inline">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input" name="membershipRadios" id="membershipRadios1" value="" checked>
                                                                        Lengkap
                                                                    </label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <label class="form-check-label">
                                                                        <input type="radio" class="form-check-input" name="membershipRadios" id="membershipRadios2" value="option2">
                                                                        Tidak Lengkap
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- Tambahkan baris-baris tabel yang lain di sini -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary mt-4">Kualitatif <i class="fas fa-solid fa-forward" style="margin-left: 3px;"></i></button>
                                    </div>
                                </div>
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
