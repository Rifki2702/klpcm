<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- bootstrap css-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <!-- fontawesome  -->
        <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
        <title>Document</title>
        <style>
            @page {
                size: legal landscape;  /* Legal size in landscape orientation */
                margin-top: 20px;
                /* margin-left: 75px; */
                margin-bottom: 0;
                /* margin-right: 75px; */
            }
            @media print {
                * {
                    -webkit-print-color-adjust: exact !important;   /* Chrome, Safari, Edge */
                    color-adjust: exact !important;                 /*Firefox*/     /*Firefox*/
                }
                .no-print, .no-print *
                {
                    display: none !important;
                }
                .card-break {
                    page-break-after: always;
                }
            /* ... the rest of the rules ... */
            }
            #loadingOverlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.8);
                z-index: 9999;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            #loadingOverlay.hidden {
                display: none;
            }
        </style>
    </head>
    <body>
        <div id="loadingOverlay">
            <div>
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p>Loading charts, please wait...</p>
            </div>
        </div>
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="fw-bold">Filter yang Dipilih : {{ ucwords($filter_waktu) }} - Periode :
                                @if ($filter_waktu == 'bulanan')
                                    {{ $bulan }}
                                @elseif ($filter_waktu == 'tahunan')
                                    {{ $tahun }}
                                @else
                                    {{ $tanggal_awal }} - {{  $tanggal_akhir }}
                                @endif
                                </h4>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.laporangrafik') }}" class="btn btn-primary no-print"></i> Kembali</a>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-2 card-break">
                            <div class="card-body" style="width: 100%">
                                <div id="LengkapTepatChartContainer" >
                                    {!! $LengkapTepatChart->container() !!}
                                </div>
                            </div>
                        </div>
                        <div class="card mt-5 card-break">
                            <div class="card-body">
                                <div id="DokterChartContainer">
                                    {!! $DokterChart->container() !!}
                                </div>
                            </div>
                        </div>
                        <div class="card mt-5 card-break">
                            <div class="card-body">
                                <div id="RuanganChartContainer">
                                    {!! $RuanganChart->container() !!}
                                </div>
                            </div>
                        </div>
                        <div class="card mt-5 card-break">
                            <div class="card-body">
                                <div id="FormulirChartContainer">
                                    {!! $FormulirChart->container() !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </body>
    <script src="{{ @asset('vendor/larapex-charts/apexcharts.js') }}"></script>
    {{-- <script src="{{ $LengkapTepatChart->cdn() }}"></script> --}}
    {{ $LengkapTepatChart->script() }}
    {{-- <script src="{{ $DokterChart->cdn() }}"></script> --}}
    {{ $DokterChart->script() }}
    {{-- <script src="{{ $RuanganChart->cdn() }}"></script> --}}
    {{ $RuanganChart->script() }}
    {{-- <script src="{{ $FormulirChart->cdn() }}"></script> --}}
    {{ $FormulirChart->script() }}
    <script>
        // function checkChartsLoaded() {
        //     return new Promise(resolve => {
        //         if (window.Chartisan && Chartisan.charts.length === 4) {
        //             resolve();
        //         } else {
        //             const interval = setInterval(() => {
        //                 if (window.Chartisan && Chartisan.charts.length === 4) {
        //                     clearInterval(interval);
        //                     resolve();
        //                 }
        //             }, 100);
        //         }
        //     });
        // }

        // console.log(Chartisan.charts.length);

        window.addEventListener('load', function () {
            setTimeout(function () {
                document.getElementById('loadingOverlay').classList.add('hidden');
                window.print();
            }, 1000);  // Delay for 1 second to ensure all charts are fully loaded
            // checkChartsLoaded().then(() => {
            // });
        });
    </script>
</html>
