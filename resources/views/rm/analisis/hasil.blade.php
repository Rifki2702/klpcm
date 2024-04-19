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
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <p>Tanggal Berkas: {{ $data->tglberkas->format('d F Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <p><strong>No RM:</strong> {{ $data->rm_pasien }}</p>
                                            <p><strong>Nama:</strong> {{ $data->pasien->nama }}</p>
                                            <p><strong>Dokter:</strong> {{ $data->dokter->nama }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12 text-center">
                                            <h3>Item Tidak Lengkap</h3>
                                        </div>
                                    </div>
                                    @foreach($data->formulirs as $formulir)
                                    <div class="row justify-content-center mb-4">
                                        <div class="col-md-3">
                                            <div class="card border">
                                                <div class="card-header">
                                                    {{ $formulir->nama_formulir }}
                                                </div>
                                                <div class="card-body">
                                                    @foreach($formulir->isiForms as $isiForm)
                                                    <p>{{ $isiForm->isi }}: {{ $isiForm->kelengkapan->kuantitatif ? 'Lengkap' : 'Tidak Lengkap' }}</p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>Lengkapi Berkas Ini Maksimal {{ $data->tglberkas->addDays(30)->format('d F Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-danger">
                                                Print <i class="fas fa-file-pdf"></i>
                                            </button>
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