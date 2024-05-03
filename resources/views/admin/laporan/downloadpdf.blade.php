<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLPCM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 80px;
            margin: 0 auto;
            padding: 10px;
        }

        h3 {
            text-align: center;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 3px;
            padding: 1px;
        }

        .card-header {
            font-weight: bold;
            font-size: 12px;
            text-align: center;
        }

        .card-body {
            font-size: 10px;
            text-align: center;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .col-md-3 {
            flex: 0 0 10%;
            max-width: 10%;
        }

        /* Kop Surat */
        .rangkasurat {
            width: 980px;
            margin: 5px;
            background-color: #fff;
            padding: 20px;
        }

        table {
            padding: 2px;
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            border: none;
            padding: 8px;
        }

        .tengah {
            text-align: center;
        }

        h1,
        h2,
        b {
            margin: 5px 0;
        }

        .kelengkapan .card {
            border: none;
            margin-bottom: 5px;
        }

        .kelengkapan .card-body {
            padding: 15px;
        }

        .kelengkapan th,
        .kelengkapan td {
            padding: 8px;
            text-align: center;
            border: none;
            font-size: small;
            /* ubah ukuran teks menjadi small */
        }

        .kelengkapan th {
            background-color: #007bff;
            color: white;
        }

        .kelengkapan tbody tr:last-child td {
            border-bottom: none;
        }

        /* Memberikan warna latar belakang pada baris ganjil */
        .kelengkapan tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="rangkasurat">
        <table>
            <tr>
                <td> <img src="{{ asset('foto/logo.png') }}" width="70px"> </td>
                <td class="tengah">
                    <h2>RUMAH SAKIT CITRA HUSADA JEMBER</h2>
                    <b>Jl. Teratai No.22, Gebang Timur, Gebang, Kec. Patrang,</b>
                    <b>Kabupaten Jember, Jawa Timur 68117</b>
                </td>
            </tr>
        </table>
        <div style="border-top: 4px solid black; width: 100%;"></div>
    </div>

    <div class="kelengkapan">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table text-center">
                                <table id="userTable" class="table display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No RM</th>
                                            <th>Nama</th>
                                            <th>Tgl Berkas</th>
                                            <th>Tgl Analisis</th>
                                            <th>Kuantitatif (%)</th>
                                            <th>Kualitatif (%)</th>
                                            <th>Dokter</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataAnalisis as $data)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $data['analisis']->pasien->rm }}</td>
                                            <td>{{ $data['analisis']->pasien->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data['analisis']->tglberkas)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data['analisis']->tglcek)->format('d/m/Y') }}</td>
                                            <td>{{ $data['persentase']['kuantitatif'] }}%</td>
                                            <td>{{ $data['persentase']['kualitatif'] }}%</td>
                                            <td>{{ $data['analisis']->user?->name }}</td>
                                            <td>
                                                @if($data['status'] == 'complete')
                                                <span class="badge badge-success">Complete</span>
                                                @elseif($data['status'] == 'imr')
                                                <span class="badge badge-warning">IMR</span>
                                                @elseif($data['status'] == 'dmr')
                                                <span class="badge badge-danger">DMR</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>