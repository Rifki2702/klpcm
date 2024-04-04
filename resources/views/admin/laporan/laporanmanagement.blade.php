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
                                <div class="table-responsive" style="text-align: center;">
                                    <table id="example" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No RM</th>
                                                <th>Nama</th>
                                                <th>Tgl Berkas</th>
                                                <th>Tgl Analisis</th>
                                                <th>Kuantitatif</th>
                                                <th>Kualitatif</th>
                                                <th>Dokter</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-right mt-4">
                                    <a href="" class="btn btn-danger "><strong>PDF</strong><i class="fas fa-file-pdf" style="margin-left: 5px;"></i></a>
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
