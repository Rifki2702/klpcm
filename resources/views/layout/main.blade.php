<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>KLPCM</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('skydash/vendors/feather/feather.css')}}">
    <link rel="stylesheet" href="{{ asset('skydash/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('skydash/vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('skydash/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ asset('skydash/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('skydash/js/select.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{ asset('skydash/css/loading.css')}}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('skydash/css/vertical-layout-light/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('skydash/images/favicon.png')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/cropperjs/dist/cropper.min.css" rel="stylesheet">

</head>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="{{ asset('skydash/images/citrus.svg')}}" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="index.html"><img src="{{ asset('skydash/images/citrus-mini.svg')}}" alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                <span class="input-group-text" id="search">
                                    <i class="icon-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                            <i class="icon-bell"></i>
                            @php
                            $unreadCount = auth()->user()->unreadNotifications->count();
                            @endphp
                            @if($unreadCount > 0)
                            <span class="count" id="notification-count"></span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="font-weight-bold">Notifications</h4>
                                </div>
                            </div>
                            <div class="notification-list" style="max-height: 200px; overflow-y: auto; width:100%">
                                @foreach(auth()->user()->notifications as $notification)
                                @php
                                $isComplete = $notification->data['is_complete'];
                                $bgColor = $isComplete ? 'bg-success' : 'bg-warning';
                                @endphp
                                {{-- --}}
                                <a href="{{ $notification->data['link'] }}" style="text-decoration: none" id="notification-read">
                                    <div class="dropdown-item preview-item @if(!$notification->read_at) unread @endif">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon {{ $bgColor }}">
                                                <i class="ti-info-alt mx-0"></i>
                                            </div>
                                        </div>
                                        <div class="preview-item-content">
                                            <h6 class="preview-subject font-weight-normal">{{ $notification->data['message'] }}</h6>
                                            <div class="d-flex justify-content-between" style="
                                                display: flex;
                                                justify-content: space-around;
                                            ">
                                                <div style="width: 500px">
                                                    <p class="font-weight-light small-text mb-0 text-muted">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="font-weight-light small-text mb-0 text-muted">
                                                        Kelengkapan data : <b class="font-weight-bold">{{ isset($notification->data['analisis']) ? $notification->data['analisis'] : '0%' }}</b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </li>
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <img src="{{ Auth::user()->image != null ? asset('storage/user/'.Auth::user()->image) : asset('skydash/images/faces/face21.jpg') }}" alt="{{ Auth::user()->name }}" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                            <a href="{{ route('admin.editprofile', Auth::user()->id) }}" class="dropdown-item">
                                <i class="fas fa-user text-primary"></i>
                                Edit Profile
                            </a>
                            <a href="{{ route('logout') }}" class="dropdown-item">
                                <i class="fas fa-sign-out-alt text-primary"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span the="icon-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item {{ Request::is('admin/dashboard', 'admin/editprofile/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-th-large menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    @can('sidebar_admin')
                    <li class="nav-item {{ Request::is('admin/usermanagement', 'admin/createuser', 'admin/edituser/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.usermanagement') }}">
                            <i class="fas fa-user menu-icon"></i>
                            <span class="menu-title">Tambah User</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('admin/doktermanagement/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.doktermanagement') }}">
                            <i class="fas fa-user-md menu-icon"></i>
                            <span class="menu-title">Tambah Dokter</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('admin/formulirmanagement', 'admin/createformulir', 'admin/createisi/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.formulirmanagement') }}">
                            <i class="fas fa-file menu-icon"></i>
                            <span class="menu-title">Tambah Form</span>
                        </a>
                    </li>
                    @endcan

                    @can('sidebar_rm')
                    <li class="nav-item {{ Request::is('admin.pasienmanagement') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.pasienmanagement') }}">
                            <i class="fas fa-user-nurse menu-icon"></i>
                            <span class="menu-title">Tambah Pasien</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('admin/analisismanagement', 'admin/analisisbaru/*', 'admin/analisislama/*', 'admin/analisiskualitatif/*', 'admin/hasil/*', 'admin/pdf/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.analisismanagement') }}">
                            <i class="fas fa-search menu-icon"></i>
                            <span class="menu-title">Analisis DRM</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('admin/laporanmanagement', 'admin/laporankualitatif/*') ? 'active' : '' }}">
                        <a class="nav-link" data-toggle="collapse" href="#laporanSubmenu" aria-expanded="false" aria-controls="laporanSubmenu">
                            <i class="icon-bar-graph menu-icon"></i>
                            <span class="menu-title">Laporan</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="laporanSubmenu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item {{ Request::is('admin/laporanmanagement') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.laporanmanagement') }}">
                                        <i class="icon-document menu-icon"></i>
                                        Keseluruhan
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/laporankualitatif/*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.laporankualitatif') }}">
                                        <i class="icon-filter menu-icon"></i>
                                        Kualitatif
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/laporanformulir/*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.laporanformulir') }}">
                                        <i class="icon-filter menu-icon"></i>
                                        Formulir
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/laporangrafik/*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.laporangrafik') }}">
                                        <i class="icon-filter menu-icon"></i>
                                        Grafik
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endcan

                    @can('sidebar_dokter')
                    <li class="nav-item {{ Request::is('admin.viewklpcm') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.viewklpcm') }}">
                            <i class="fas fa-chart-bar menu-icon"></i>
                            <span class="menu-title">View KLPCM</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </nav>
            <!-- partial -->
            @yield('content')
            <!-- main-panel ends -->

        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <script src="{{ asset('skydash/vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('skydash/vendors/chart.js/Chart.min.js')}}"></script>
    <script src="{{ asset('skydash/vendors/datatables.net/jquery.dataTables.js')}}"></script>
    <script src="{{ asset('skydash/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
    <script src="{{ asset('skydash/vendors/chart.js/Chart.min.js')}}"></script>
    <script src="{{ asset('skydash/js/dataTables.select.min.js')}}"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('skydash/js/off-canvas.js')}}"></script>
    <script src="{{ asset('skydash/js/hoverable-collapse.js')}}"></script>
    <script src="{{ asset('skydash/js/template.js')}}"></script>
    <script src="{{ asset('skydash/js/settings.js')}}"></script>
    <script src="{{ asset('skydash/js/todolist.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ asset('skydash/js/dashboard.js')}}"></script>
    <script src="{{ asset('skydash/js/Chart.roundedBarCharts.js')}}"></script>
    <script src="{{ asset('skydash/js/password.js')}}"></script>
    <script src="{{ asset('skydash/js/upload.js')}}"></script>
    <script src="{{ asset('skydash/js/imageUploader.js')}}"></script>
    <script src="{{ asset('skydash/js/chart.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/js/bootstrap.min.js" integrity="sha384-Jg2f/XvOoFIoqw/i5X+TYjI2YJT0QCsNQ+0Jgy9P1kD2a8swITZcK7vjpM6dkA2t" crossorigin="anonymous"></script>
    @stack('js')
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
                "dom": '<"row"<"col-sm-1"l><"col-sm-11"f>><"row"<"col-sm-12"t>><"row"<"col-sm-3"i><"col-sm-9"p>>',
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records",
                }
            });
        });
    </script>
    <script>
        function updateChart(timeFrame) {
            fetch(`/dashboard/chart/${timeFrame}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('kuantitatifChartContainer').innerHTML = data.chart;
                })
                .catch(error => console.error('Error loading the chart:', error));
        }
    </script>
    <script>
        $('#notification-read').on('click', function() {
            let id = `{{ Auth::user()->id }}`
            $.ajax({
                url: "{{ route('notifications.markAsRead') }}",
                type: 'GET',
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                }
            })
        })
    </script>
    {{-- <script>
        // Menghilangkan jumlah notifikasi setelah diklik
        document.addEventListener('DOMContentLoaded', function() {
            var countElement = document.getElementById('notification-count');
            if (countElement) {
                countElement.addEventListener('click', function() {
                    countElement.style.display = 'none';
                    // Mengirim request ke backend untuk menandai notifikasi sebagai sudah dibaca
                    // Route [ notifications.markAsRead ] not defined, hence removed the fetch call
                });
            }
        });
    </script> --}}

    <!-- End custom js for this page-->
</body>

</html>