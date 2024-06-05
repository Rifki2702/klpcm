@extends('layout.main')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="row">
          <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Welcome {{ session('username') }}</h3>
            <h6 class="font-weight-normal mb-0">Hampir Sempurna, Kelengkapan Sudah Mencapai <span class="text-primary">jj</span>. Ayo Tingkatkan</h6>
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
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-black mb-3">Petugas</div>
                <div class="h5 mb-0 text-primary fs-30">{{ $jumlahUser }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-users fa-3x text-reddit"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold  text-black mb-3">Pasien</div>
                <div class="h5 mb-0 text-primary fs-30">{{ $jumlahPasien }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-hospital-user fa-3x text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-black mb-3">Jumlah Analisis</div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">
                    <div class="h5 mb-0 mr-3 text-primary fs-30">{{ $jumlahAnalisis }}</div>
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

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-black mb-3">Tidak Lengkap</div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">
                    <div class="h5 mb-0 mr-3 text-primary fs-30">{{ $jumlahTidakLengkap->count() }}</div>
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

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div id="kualitatifChartContainer">
              {!! $KualitatifChart->container() !!}
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div id="kuantitatifChartContainer">
              {!! $KuantitatifChart->container() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="{{ $KuantitatifChart->cdn() }}"></script>
{{ $KuantitatifChart->script() }}
<script src="{{ $KualitatifChart->cdn() }}"></script>
{{ $KualitatifChart->script() }}
@endsection