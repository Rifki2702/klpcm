<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Formulir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 5px;
        }

        .card-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        .print-button {
            margin-bottom: 20px;
            text-align: right;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
    <script>
        function initiatePrint() {
            window.print();
        }
        window.onload = initiatePrint;
    </script>
</head>

<body>
    <div class="header">
        <h1>Laporan Formulir</h1>
    </div>

    <div class="print-button">
        <button onclick="initiatePrint()">Print</button>
    </div>

    @if (isset($dataFormulir))
    @foreach ($dataFormulir as $formulir)
    <div class="card">
        <div class="card-header">
            {{ $formulir['nama_formulir'] }}
        </div>
        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th>Isi Formulir</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($formulir['isi_formulir'] as $isi)
                    <tr>
                        <td>{{ $isi['isi'] }}</td>
                        <td>{{ $isi['persentase_lengkap'] }}%</td>
                    </tr>
                    @endforeach
                    @if (empty($formulir['isi_formulir']))
                    <tr>
                        <td colspan="2">Tidak ada data yang tersedia</td>
                    </tr>
                    @else
                    <tr>
                        <td><strong>Total Data:</strong> {{ count($formulir['isi_formulir']) }}</td>
                        <td><strong>Rata-rata Persentase:</strong> {{ number_format(collect($formulir['isi_formulir'])->avg('persentase_lengkap'), 2) }}%</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
    @endif

    <div class="footer">
        <p>&copy; 2024 Your Company</p>
    </div>
</body>

</html>