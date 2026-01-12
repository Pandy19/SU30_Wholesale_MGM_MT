<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Wholesale MGM')</title>

  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('assets/plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('assets/plugins/summernote/summernote-bs4.min.css')}}">
  <style>
  .nav-icon-btn{
    border-radius: 10px;
    transition: .15s ease-in-out;
  }
  .nav-icon-btn:hover{
    background: #f2f4f6;
  }
  .navbar-badge.badge-pill{
    border-radius: 999px;
    padding: .25em .5em;
    font-size: .7rem;
  }
</style>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{asset('assets/dist/img/MMOLOGO2.png')}}" alt="AdminLTELogo" height="100" width="100">
  </div>

  <!-- Navbar -->
 <!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <!-- Sidebar toggle -->
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>

    <!-- Page title (optional) -->
    <li class="nav-item d-none d-md-inline-block">
      <span class="nav-link text-dark font-weight-bold">
        @yield('page-title', 'Dashboard')
      </span>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto align-items-center">

    <!-- Search icon -->
    <li class="nav-item">
      <a class="nav-link nav-icon-btn" data-widget="navbar-search" href="#" role="button" title="Search">
        <i class="fas fa-search"></i>
      </a>

      <div class="navbar-search-block">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar"
                   type="search"
                   placeholder="Search anything..."
                   aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
              <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <!-- Messages -->
    <li class="nav-item dropdown">
      <a class="nav-link nav-icon-btn" data-toggle="dropdown" href="#" title="Messages">
        <i class="far fa-comments"></i>
        <span class="badge badge-danger navbar-badge badge-pill">3</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header font-weight-bold">3 Messages</span>
        <div class="dropdown-divider"></div>

        <a href="#" class="dropdown-item">
          <div class="media">
            <img src="{{asset('assets/dist/img/user1-128x128.jpg')}}"
                 alt="User Avatar"
                 class="img-size-50 mr-3 img-circle">
            <div class="media-body">
              <h3 class="dropdown-item-title mb-0">
                Brad Diesel
                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm mb-0">Call me whenever you can...</p>
              <p class="text-sm text-muted mb-0"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
        </a>

        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
      </div>
    </li>

    <!-- Notifications -->
    <li class="nav-item dropdown">
      <a class="nav-link nav-icon-btn" data-toggle="dropdown" href="#" title="Notifications">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge badge-pill">15</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header font-weight-bold">15 Notifications</span>
        <div class="dropdown-divider"></div>

        <a href="#" class="dropdown-item">
          <i class="fas fa-envelope mr-2 text-primary"></i> 4 new messages
          <span class="float-right text-muted text-sm">3 mins</span>
        </a>

        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-users mr-2 text-success"></i> 8 friend requests
          <span class="float-right text-muted text-sm">12 hours</span>
        </a>

        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li>

    <!-- Fullscreen -->
    <li class="nav-item">
      <a class="nav-link nav-icon-btn" data-widget="fullscreen" href="#" role="button" title="Fullscreen">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    <!-- User dropdown -->
    <li class="nav-item dropdown ml-2">
      <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
        <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}"
             class="img-circle elevation-1 mr-2"
             style="width:32px;height:32px;object-fit:cover;"
             alt="User">
        <span class="d-none d-md-inline font-weight-bold">Admin</span>
        <i class="fas fa-angle-down ml-2 text-muted"></i>
      </a>

      <div class="dropdown-menu dropdown-menu-right">
        <a href="#" class="dropdown-item">
          <i class="fas fa-user mr-2"></i> Profile
        </a>
        <a href="#" class="dropdown-item">
          <i class="fas fa-cog mr-2"></i> Settings
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ url('/logout') }}" class="dropdown-item text-danger">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </li>

  </ul>
</nav>
<!-- /.navbar -->

  <!-- /.navbar -->