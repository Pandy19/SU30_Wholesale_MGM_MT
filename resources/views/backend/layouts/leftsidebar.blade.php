  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{asset('assets/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Wholesale MGM</span>
    </a>
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('assets/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Admin</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
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

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{url('/')}}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
              Dashboard
              </p>
            </a>
          </li>

          <li class="nav-header">SUPPLIERS</li>
          <li class="nav-item">
            <a href="{{url('/suppliers')}}" class="nav-link">
              <i class="fas fa-circle nav-icon"></i>
              <p>Brand Suppliers</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/product_management')}}" class="nav-link">
              <i class="fas fa-circle nav-icon"></i>
              <p>Product Management</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/supplier_orders')}}" class="nav-link">
              <i class="fas fa-circle nav-icon"></i>
              <p>Supplier Order History</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/goods_receiving')}}" class="nav-link">
              <i class="fas fa-circle nav-icon"></i>
              <p>Goods Receiving</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/supplier_returns')}}" class="nav-link">
              <i class="fas fa-circle nav-icon"></i>
              <p>Goods Return</p>
            </a>
          </li>
          
          <li class="nav-header">STOCKS</li>
          <li class="nav-item">
            <a href="{{url('/approved_good_stock')}}" class="nav-link">
              <i class="fas fa-circle nav-icon"></i>
              <p>Approved Goods</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/stock_categorys')}}" class="nav-link">
              <i class="fas fa-circle nav-icon"></i>
              <p>Stock Categorys</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/product_stock_list')}}" class="nav-link">
              <i class="fas fa-circle nav-icon"></i>
              <p>Stock Lists</p>
            </a>
          </li>
      </nav>
    </div>
  </aside>