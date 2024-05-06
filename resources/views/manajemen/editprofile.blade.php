@extends('layout.main')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <img src="{{ Auth::user()->image != null ? asset('storage/user/'.Auth::user()->image) : asset('skydash/images/faces/face21.jpg') }}" alt="{{ Auth::user()->name }}" />
                            <h3>{{ Auth::user()->name }}</h3>
                            <p>{{ Auth::user()->roles->first()->name }}</p>
                            <p>Email: {{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-body">
                            <h4 class="card-title">Ubah Identitas</h4>
                            <form action="{{ route('admin.updateuser', ['id' => Auth::user()->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="gender">Jenis Kelamin</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="Laki-laki" {{ Auth::user()->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ Auth::user()->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password Baru</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru jika ingin mengubah">
                                </div>
                                <div class="form-group">
                                    <label for="image">Foto</label>
                                    <input type="file" class="form-control-file" id="image" name="image">
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection