<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Item Tidak Lengkap</title>
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
            border: 1px solid #000;
            border-radius: 5px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            margin-top: 40px;
        }

        .header img {
            width: 70px;
            margin-right: 20px;
        }

        .header div {
            text-align: center;
            flex: 1;
        }

        .header div h2 {
            margin: 0;
        }

        .header div p {
            margin: 5px 0;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 5px 0;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .incomplete-items {
            text-align: center;
            margin-bottom: 20px;
        }

        .rangkasurat {
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            /* Add margin-bottom to create space */
        }

        table {
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

        .card {
            display: inline-block;
            width: 22%;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin: 10px;
            vertical-align: top;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 10px;
            font-weight: bold;
        }

        .card-body {
            padding: 10px;
        }

        .buttons {
            text-align: center;
            margin-top: 20px;
        }

        .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 14px;
            cursor: pointer;
            margin: 0 10px;
        }

        .buttons button:hover {
            background-color: #0056b3;
        }

        @media print {
            .buttons {
                display: none;
            }

            .header {
                page-break-before: always;
                position: fixed;
                top: 0;
                width: 100%;
            }

            body {
                margin-top: 150px;
                /* Adjust this value to ensure the content doesn't overlap the header */
            }
        }
    </style>
</head>

<body>
    <table class="rangkasurat">
        <tr>
            <td>
                <img src="{{ asset('foto/logo.png') }}" width="70px">
            </td>
            <td class="tengah">
                <h2>RUMAH SAKIT CITRA HUSADA JEMBER</h2>
                <b>Jl. Teratai No.22, Gebang Timur, Gebang, Kec. Patrang,</b>
                <b>Kabupaten Jember, Jawa Timur 68117</b>
            </td>
        </tr>
    </table>
    <div class="container">
        <div class="header">
            <p>Tanggal Berkas: 13/06/2024</p>
        </div>
        <div class="info">
            <p><strong>No RM:</strong> 827656</p>
            <p><strong>Nama:</strong> Rifki Fadilah</p>
            <p><strong>Ruangan:</strong> Anturium</p>
            <p><strong>Dokter:</strong> dr. Fadil</p>
            <p><strong>Kuantitatif:</strong> 84.62%</p>
            <p><strong>Kualitatif:</strong> 85.71%</p>
        </div>
        <div class="title">
            <h3>Item Tidak Lengkap</h3>
        </div>
        <div class="incomplete-items">
            <div class="card">
                <div class="card-header">Resume Pasien Pulang</div>
                <div class="card-body">
                    <p>Tanggal Keluar</p>
                    <p>Riwayat Alergi</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">CPPT</div>
                <div class="card-body">
                    <p>Nama</p>
                    <p>Ruang/Kelas</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Ringkasan Masuk dan Keluar</div>
                <div class="card-body">
                    <p>Tgl Lahir</p>
                    <p>Alamat</p>
                    <p>Gol Darah</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Informed Consent</div>
                <div class="card-body">
                    <p>Nama</p>
                </div>
            </div>
        </div>
        <div class="buttons">
            <button onclick="window.history.back()">Kembali</button>
            <button onclick="window.print()">Cetak</button>
        </div>
    </div>
</body>

</html>