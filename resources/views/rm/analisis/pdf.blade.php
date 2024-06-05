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
            max-width: 800px;
            margin: 0 auto;
            padding: 10px;
        }

        h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 10px;
            padding: 10px;
            position: relative;
        }

        .card-header {
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }

        .card-body {
            font-size: 12px;
            text-align: center;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-bottom: 10px;
        }

        .col-sm-3 {
            flex: 0 0 22%;
            max-width: 22%;
            margin-bottom: 10px;
        }

        .col-sm-6 {
            flex: 0 0 48%;
            max-width: 48%;
            margin-bottom: 10px;
        }

        .col-sm-8 {
            flex: 0 0 66%;
            max-width: 66%;
            margin-bottom: 10px;
        }

        .col-sm-12 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 10px;
        }

        .text-right {
            text-align: right;
        }

        /* Kop Surat */
        .rangkasurat {
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-sizing: border-box;
        }

        table {
            border-bottom: 5px solid #000;
            width: 100%;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        td {
            vertical-align: top;
        }

        .tengah {
            text-align: center;
        }

        h1,
        h2,
        b {
            margin: 5px 0;
        }

        hr {
            border: 1px solid #ccc;
            margin: 20px 0;
        }

        .date-top-right {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 12px;
            margin-top: 5px;
        }

        .text-left {
            text-align: left;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 14px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="rangkasurat">
        <table>
            <tr>
                <td><img src="{{ asset('foto/logo.png') }}" width="70px"></td>
                <td class="tengah">
                    <h2>RUMAH SAKIT CITRA HUSADA JEMBER</h2>
                    <b>Jl. Teratai No.22, Gebang Timur, Gebang, Kec. Patrang,</b>
                    <b>Kabupaten Jember, Jawa Timur 68117</b>
                </td>
            </tr>
        </table>
    </div>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="date-top-right">
                            <p>Tanggal Berkas: {{ date('d/m/Y') }}</p>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-12 text-left">
                                        <p><strong>No RM:</strong> {{ $analisis->pasien->rm }}</p>
                                        <p><strong>Nama:</strong> {{ $analisis->pasien->name }}</p>
                                        <p><strong>Ruangan:</strong> {{ $analisis->user->name }}</p>
                                        <p><strong>Dokter:</strong>
                                            @if($analisis->dokter)
                                            {{ $analisis->dokter->nama_dokter }}
                                            @else
                                            Tidak ada dokter yang ditugaskan
                                            @endif
                                        </p>
                                        <p><strong>Kuantitatif:</strong> {{ number_format($persentaseKuantitatif, 2) }}%</p>
                                        <p><strong>Kualitatif:</strong> {{ number_format($persentaseKualitatif, 2) }}%</p>
                                    </div>
                                </div>
                                <hr>
                                <h3>Item Tidak Lengkap</h3>

                                @php
                                $formulirIds = [];
                                if ($analisis->kelengkapans) {
                                $formulirIds = $analisis->kelengkapans->where('kuantitatif', 0)->pluck('formulir_id')->unique();
                                }
                                @endphp
                                <div class="row justify-content-center">
                                    @foreach ($formulirIds as $index => $formulirId)
                                    <div class="col-sm-3">
                                        <div class="card border">
                                            <div class="card-header">
                                                <h5><strong>{{ $analisis->kelengkapans->where('formulir_id', $formulirId)->first()->formulir->nama_formulir }}</strong></h5>
                                            </div>
                                            <div class="card-body">
                                                @foreach ($analisis->kelengkapans->where('formulir_id', $formulirId) as $kelengkapan)
                                                <p>{{ $kelengkapan->isiForm->isi }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @if (($index + 1) % 4 == 0 && $index + 1 != count($formulirIds))
                                </div>
                                <div class="row justify-content-center">
                                    @endif
                                    @endforeach
                                </div>
                                <button onclick="window.print()">Print</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>