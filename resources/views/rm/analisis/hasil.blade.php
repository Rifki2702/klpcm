@extends('layout.main')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row"> <!-- Tambahkan class justify-content-center -->
            <div class="col-md-12 grid-margin stretch-card"> <!-- Ubah ukuran kolom menjadi col-md-8 -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="container">
                                    <div class="row mb-4">
                                        <div class="col-md-6 text-right">
                                            <p>Tanggal Berkas: {{ date('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <p><strong>No RM:</strong> {{ $analisis->pasien->rm }}</p>
                                            <p><strong>Nama:</strong> {{ $analisis->pasien->name }}</p>
                                            <p><strong>Dokter:</strong> {{ $analisis->user->name }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <hr>
                                            <h3 class="text-center">Item Tidak Lengkap</h3>
                                        </div>
                                    </div>

                                    @php
                                    $formulirIds = $analisis->kelengkapans->where('kuantitatif', 0)->pluck('formulir_id')->unique();
                                    @endphp
                                    <div class="row justify-content-center"> <!-- Tambahkan class justify-content-center -->
                                        @foreach ($formulirIds as $formulirId)
                                        <div class="col-md-3 mb-4">
                                            <div class="card border">
                                                <div class="card-header text-center">
                                                    <h5><strong>{{ $analisis->kelengkapans->where('formulir_id', $formulirId)->first()->formulir->nama_formulir }}</strong></h5>
                                                </div>
                                                <div class="card-body text-center">
                                                    @foreach ($analisis->kelengkapans->where('formulir_id', $formulirId) as $kelengkapan)
                                                    <p>{{ $kelengkapan->isiForm->isi }}</p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @if ($loop->iteration % 4 == 0)
                                    </div>
                                    <div class="row justify-content-center"> <!-- Tambahkan class justify-content-center -->
                                        @endif
                                        @endforeach
                                    </div>

                                    <!-- Tombol untuk cetak PDF dan kembali ke halaman analisis lama -->
                                    <div class="row mt-4 justify-content-end">
                                        <div class="col text-right">
                                            <a href="{{ route('admin.analisislama', ['id' => $analisis->pasien_id]) }}" class="btn btn-primary mr-2">
                                                <i class="fa fa-arrow-left"></i> Kembali
                                            </a>
                                            <a href="{{ route('admin.hasil.pdf', ['analisis_id' => $analisis->id]) }}" class="btn btn-success">
                                                <i class="fa fa-print"></i> Cetak
                                            </a>
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