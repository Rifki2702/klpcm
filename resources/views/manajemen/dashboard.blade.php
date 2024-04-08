@extends('layout.main')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="row">
          <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Welcome {{ session('username') }}</h3>
            <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6>
          </div>
          <div class="col-12 col-xl-4">
            <div class="justify-content-end d-flex">
              <i class="mdi mdi-calendar"></i> Today ({{ \Illuminate\Support\Carbon::now()->format('d M Y') }})
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-black mb-3">Jumlah Petugas</div>
                <div class="h5 mb-0 text-primary fs-30">{{ $jumlahUser }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-users fa-3x text-reddit"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold  text-black mb-3">Jumlah Pasien</div>
                <div class="h5 mb-0 text-primary fs-30">{{ $jumlahPasien }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-hospital-user fa-3x text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-black mb-3">Jumlah Formulir</div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">
                    <div class="h5 mb-0 mr-3 text-primary fs-30">{{ $jumlahFormulir }}</div>
                  </div>
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clipboard-list fa-3x text-facebook"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    @can('view_table_on_dashboard')

    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title">Advanced Table</p>
            <div class="row">
              <div class="col-12">
                <div class="table-responsive">
                  <table id="example" class="display expandable-table" style="width:100%">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>No RM</th>
                        <th>Tanggal Pengecekan</th>
                        <th>LTL</th>
                        <th>Keterangan</th>
                        <th>Tindakan</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Kualitatif</h4>
            <canvas id="areaChart"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Kuantitatif</h4>
            <canvas id="lineChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    @endcan
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <!-- partial -->
  </div>
  @endsection