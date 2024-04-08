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
                                <div class="text mb-3">No RM: <strong class="text-Black">{{ $rm_pasien }}</strong></div>
                                <div class="row">
                                    <div class="col">
                                        <div class="table-responsive table-striped" style="text-align: center;">
                                            <table id="example" class="display expandable-table" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tanggal Berkas</th>
                                                        <th>Kuantitatif</th>
                                                        <th>Kualitatif</th>
                                                        <th>Tindakan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>12 Maret</td>
                                                        <td>80%</td>
                                                        <td>100%</td>
                                                        <td></td>
                                                </tbody>
                                            </table>
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