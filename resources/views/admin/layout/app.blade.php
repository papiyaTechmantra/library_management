<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings[4]->content }} - @yield('page-title')</title>
     <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('backend-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend-assets/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend-assets/css/style.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>

    {{-- <script src="//cdn.ckeditor.com/4.9.2/full/ckeditor.js"></script> --}}

    @yield('style')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <!-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/') }}" class="nav-link" target="_blank">Website</a>
                </li> -->
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    {{ Auth::check() ? Auth::user()->name : "" }}
                </a>
            </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- <a href="{{ url('/') }}" class="brand-link text-center admin_web_logo" target="_blank">
                <img src="" alt="AdminLTE Logo" class="elevation-3" style="height: 45px;opacity: .8">
                {{-- <span class="brand-text font-weight-light">Admin</span> --}}
            </a> -->

            <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('backend-assets/images/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::check() ? Auth::user()->name : "" }}</a>
                </div>
            </div>
            
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
                </div>
            </div>
            @include('admin.layout.sidebar')
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@yield('page-title')</li>
                        </ol>
                    </div>
                    </div>
                </div>
            </div>
            @yield('section')
        </div>


        <!-- <aside class="control-sidebar control-sidebar-dark">
            <div class="p-3">
                <h5>Profile</h5>
                <p>Change Password</p>
            </div>
        </aside> -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                &copy; {{ $settings[3]->content }} All rights reserved
            </div>
            <strong>{{ date('Y') }} - <a href="{{ url('/') }}" target="_blank">{{ $settings[3]->content }}</a></strong>
        </footer>
    </div>
    <form action="{{ route('admin.logout') }}" id="logout-form" method="post" class="d-none">@csrf</form>

    <script src="{{ asset('backend-assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend-assets/js/adminlte.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend-assets/js/custom.js') }}"></script>
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script> --}}

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script>
        @if(Session::get('success'))
            toastFire('success', '{{Session::get("success")}}');
        @endif

        @if(Session::get('failure'))
            toastFire('error', '{{Session::get("failure")}}');
        @endif
    </script>

    @yield('script')
</body>
</html>