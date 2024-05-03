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
            margin-bottom: 2px;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 2px;
            padding: 1px;
        }

        .card-header {
            font-weight: bold;
            font-size: 12px;
            text-align: center;
            margin-bottom: 2px;
        }

        .card-body {
            font-size: 10px;
            text-align: center;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-bottom: 1px;
        }

        .col-md-3 {
            flex: 0 0 10%;
            max-width: 10%;
            margin-bottom: 2px;
        }

        /* Kop Surat */
        .rangkasurat {
            width: 980px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
        }

        table {
            border-bottom: 5px solid #000;
            padding: 2px;
            width: 100%;
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
    </div>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-6 text-right">
                                        <p>Tanggal Berkas: {{ date('d/m/Y') }}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p><strong>No RM:</strong> {{ $analisis->pasien->rm }}</p>
                                        <p><strong>Nama:</strong> {{ $analisis->pasien->name }}</p>
                                        <p><strong>Dokter:</strong> {{ $analisis->user->name }}</p>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>