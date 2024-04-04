<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('skydash/css/vertical-layout-light/style.css')}}">

    <title>Login</title>
    <style>
        /* Custom styles for centering the container */
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('foto/bg.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .container {
            max-width: 500px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container py-5">
    <label style="font-weight: bold; display: block; text-align: center;">WELCOME</label>
      <div class="border rounded px-4 py-4" style="background-color: rgba(255, 255, 255, 0.4);">
            <form action="{{ route('login-proses') }}" method="POST">
                @csrf
                <div class="mb-3"> 
                    <label for="email" class="form-label">Email</label>
                    <input type="email" value="{{ old('email') }}" name="email" class="form-control" placeholder="email"> @error('email')
                    <small>{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="password"> @error('password')
                    <small>{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-2 text-center">
                  <button name="submit" type="submit" class="btn btn-light btn-md font-weight-bold">Login</button>
              </div>
            </form>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if($message = Session::get('success'))
    <script>
        Swal.fire('{{ $message }}');
    </script>
@endif

@if($message = Session::get('failed'))
    <script>
        Swal.fire('{{ $message }}');
    </script>
@endif
</body>
</html>


