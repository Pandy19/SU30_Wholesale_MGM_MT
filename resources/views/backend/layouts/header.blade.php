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
  @stack('styles')
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

    <!-- Notifications -->
    <li class="nav-item dropdown">
      @php
        $pendingSuppliersCount = \App\Models\Supplier::where('status', 'pending')->count();
        $pendingGRNCount = \App\Models\GoodsReceiving::where('status', 'pending')->count();
        $pendingStockCount = \App\Models\GoodsReceivingItem::where('is_stocked', false)->where('received_qty', '>', 0)->count();
        $totalNotifications = $pendingSuppliersCount + $pendingGRNCount + $pendingStockCount;
      @endphp
      <a class="nav-link nav-icon-btn" data-toggle="dropdown" href="#" title="Notifications">
        <i class="far fa-bell"></i>
        @if($totalNotifications > 0)
          <span class="badge badge-warning navbar-badge badge-pill">{{ $totalNotifications }}</span>
        @endif
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow border-0">
        <span class="dropdown-item dropdown-header font-weight-bold bg-light">{{ $totalNotifications }} Pending Actions</span>
        
        @if($pendingSuppliersCount > 0)
          <div class="dropdown-divider"></div>
          <a href="{{ route('suppliers.approvals') }}" class="dropdown-item">
            <i class="fas fa-user-plus mr-2 text-primary"></i> {{ $pendingSuppliersCount }} Supplier Approvals
            <span class="float-right badge badge-warning">{{ $pendingSuppliersCount }}</span>
          </a>
        @endif

        @if($pendingGRNCount > 0)
          <div class="dropdown-divider"></div>
          <a href="{{ route('goods_receiving.index') }}" class="dropdown-item">
            <i class="fas fa-truck-loading mr-2 text-info"></i> {{ $pendingGRNCount }} GRN Pending
            <span class="float-right badge badge-info">{{ $pendingGRNCount }}</span>
          </a>
        @endif

        @if($pendingStockCount > 0)
          <div class="dropdown-divider"></div>
          <a href="{{ route('approved_good_stock.index') }}" class="dropdown-item">
            <i class="fas fa-boxes mr-2 text-success"></i> {{ $pendingStockCount }} Stock Approval
            <span class="float-right badge badge-success">{{ $pendingStockCount }}</span>
          </a>
        @endif

        @if($totalNotifications == 0)
          <div class="dropdown-divider"></div>
          <div class="dropdown-item text-center text-muted small py-3">
            <i class="fas fa-check-circle mr-1 text-success"></i> Everything is up to date!
          </div>
        @endif
        
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">View All Activities</a>
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
      @php
        $authUser = auth()->user();
        $userName = $authUser->name ?? 'Guest';
        // Display role from role_id if available, otherwise from role string
        $userRole = 'User';
        if ($authUser) {
            if ($authUser->role_id) {
                $roleModel = \App\Models\Role::find($authUser->role_id);
                $userRole = $roleModel ? $roleModel->name : ucfirst($authUser->role);
            } else {
                $userRole = ucfirst($authUser->role);
            }
        }
        
        $profilePic = ($authUser && $authUser->profile_picture) 
          ? asset('storage/' . $authUser->profile_picture) 
          : asset('assets/dist/img/MMOLOGO1.png');
      @endphp
      <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
        <img src="{{ $profilePic }}"
             class="img-circle elevation-1 mr-2"
             style="width:32px;height:32px;object-fit:cover;"
             alt="User">
        <span class="d-none d-md-inline font-weight-bold">
            {{ $userName }} [{{ $userRole }}]
        </span>
        <i class="fas fa-angle-down ml-2 text-muted"></i>
      </a>

      <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-divider"></div>
        <a href="{{ route('setting.index') }}" class="dropdown-item">
          <i class="fas fa-user mr-2"></i> Profile Settings
        </a>
        <div class="dropdown-divider"></div>
        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
            @csrf
        </form>
        <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </li>

  </ul>
</nav>
<!-- /.navbar -->

  <!-- /.navbar -->