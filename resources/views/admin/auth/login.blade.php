<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Log in</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('backend-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend-assets/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend-assets/css/style.css') }}">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('backend-assets/images/logo.jpg') }}" alt="logo" style="height: 100px">
            </a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form method="POST" action="{{ route('admin.check') }}">@csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Username" name="username" value="{{old('username')}}" autocomplete="email" maxlength="30" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" name="password" value="" autocomplete="none" maxlength="30">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        {{-- <input type="password" class="form-control" placeholder="Password"> --}}
                    </div>
                    <div class="row">
                        {{-- <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                        </div> --}}
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>

                {{-- <div class="social-auth-links text-center mb-3">
                    <p>- OR -</p>
                    <a href="#" class="btn btn-block btn-primary">
                        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                    </a>
                    <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                    </a>
                </div> --}}
            </div>
        </div>
    </div>

    <script src="{{ asset('backend-assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend-assets/js/adminlte.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend-assets/js/custom.js') }}"></script>

    <script>
        @if(Session::get('success'))
            toastFire('success', '{{Session::get("success")}}');
        @endif

        @if(Session::get('failure'))
            toastFire('error', '{{Session::get("failure")}}');
        @endif
    </script>
</body>
</html>







{{-- @extends('admin.layout.app')
@section('page-title', 'login')

@section('section')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4 mt-5">
            <div class="card shadow">
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('admin.check') }}">@csrf
                        <h3 class="page_header">ADMIN Login</h3>

                        <p id="loginText" class="subtitle1 fw-400 mb-4">login with Username &amp; password</p>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control form-control-sm @error('username') is-invalid @enderror" id="username" placeholder="Username" name="username" value="{{old('username')}}" autocomplete="mobile-number" maxlength="30" autofocus>
                            <label for="username">Username *</label>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-2">
                            <input type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" id="password" placeholder="Password" name="password" value="" autocomplete="none" maxlength="30">
                            <label for="password">Password *</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary" id="login_button" name="login_button"> Continue </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}