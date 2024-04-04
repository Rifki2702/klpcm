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
                            <i class="icon-bell mx-0"></i>
                            <span class="count"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="ti-info-alt mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Application Error</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        Just now
                                    </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-warning">
                                        <i class="ti-settings mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Settings</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        Private message
                                    </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-info">
                                        <i class="ti-user mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">New user registration</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        2 days ago
                                    </p>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <img src="{{ asset('storage/foto-user/'.Auth::user()->image) }}" alt="{{ Auth::user()->name }}" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item">
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
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-th-large menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    @can('sidebar_admin')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin.createuser') ? 'active' : '' }}" href="{{ route('admin.usermanagement') }}">
                            <i class="fas fa-user menu-icon"></i>
                            <span class="menu-title">Tambah User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin.createformulir') ? 'active' : '' }}" href="{{ route('admin.formulirmanagement') }}">
                            <i class="fas fa-file menu-icon"></i>
                            <span class="menu-title">Tambah Form</span>
                        </a>
                    </li>
                    @endcan

                    @can('sidebar_rm')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.pasienmanagement') }}">
                            <i class="fas fa-user-nurse menu-icon"></i>
                            <span class="menu-title">Tambah Pasien</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.analisismanagement') }}">
                            <i class="fas fa-search menu-icon"></i>
                            <span class="menu-title">Analisis DRM</span>
                        </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.laporanmanagement') }}">
                            <i class="icon-bar-graph menu-icon"></i>
                            <span class="menu-title">Laporan</span>
                        </a>
                    </li>
                    @endcan

                    @can('sidebar_dokter')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.viewklpcm') }}">
                            <i class="fas fa-chart-bar menu-icon"></i>
                            <span class="menu-title">View KLPCM</span>
                        </a>
                    </li>
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
    <!-- container-scroller -->

    <!-- plugins:js -->
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
    <!-- End custom js for this page-->
</body>

</html>