<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ auth()->user()->role === 'supplier' ? route('Supplier_Dashboard.index') : url('/') }}" class="brand-link">
    <img src="{{ asset('assets/dist/img/MMOLOGO2.png') }}"
         alt="AdminLTE Logo"
         class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text font-weight-light">Wholesale MGM</span>
  </a>

  <div class="sidebar">

    <!-- Sidebar Search -->
    <div class="form-inline mt-4">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
          data-accordion="false">

        @if(auth()->user()->role !== 'supplier')
        <!-- DASHBOARD -->
        <li class="nav-item">
          <a href="{{ url('/') }}"
             class="nav-link {{ request()->path() === '/' ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        @if(in_array(auth()->user()->role, ['owner', 'admin']))
        <li class="nav-item">
          <a href="{{ route('user_management.index') }}"
             class="nav-link {{ request()->routeIs('user_management.index') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>User Management</p>
          </a>
        </li>
        @endif

        <!-- SUPPLIERS -->
        <li class="nav-header">PROCUREMENT</li>

        <li class="nav-item">
          <a href="{{ url('/suppliers') }}"
             class="nav-link {{ request()->is('suppliers') ? 'active' : '' }}">
            <i class="nav-icon fas fa-industry"></i>
            <p>Supplier Brands</p>
          </a>
        </li>

        @if(in_array(auth()->user()->role, ['owner', 'admin']))
        <li class="nav-item">
          <a href="{{ route('suppliers.approvals') }}"
             class="nav-link {{ request()->routeIs('suppliers.approvals') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-check"></i>
            <p>
              Supplier Approvals
              @php
                  $pendingCount = \App\Models\Supplier::where('status', 'pending')->count();
              @endphp
              @if($pendingCount > 0)
                  <span class="badge badge-warning right">{{ $pendingCount }}</span>
              @endif
            </p>
          </a>
        </li>
        @endif

        @if(in_array(auth()->user()->role, ['owner', 'admin', 'staff']))
        <li class="nav-item">
          <a href="{{ url('/product_management') }}"
             class="nav-link {{ request()->is('product_management') ? 'active' : '' }}">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Supplier Products</p>
          </a>
        </li>
        @endif

        @if(in_array(auth()->user()->role, ['owner', 'admin', 'staff']))
        <li class="nav-item">
          <a href="{{ url('/supplier_orders') }}"
             class="nav-link {{ request()->is('supplier_orders') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-invoice"></i>
            <p>Purchase Orders</p>
          </a>
        </li>
        @endif

        @if(in_array(auth()->user()->role, ['owner', 'admin', 'inspector']))
        <li class="nav-item">
          <a href="{{ url('/goods_receiving') }}"
             class="nav-link {{ request()->is('goods_receiving') ? 'active' : '' }}">
            <i class="nav-icon fas fa-truck-loading"></i>
            <p>Goods Receiving</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ url('/supplier_returns') }}"
             class="nav-link {{ request()->is('supplier_returns') ? 'active' : '' }}">
            <i class="nav-icon fas fa-undo-alt"></i>
            <p>Supplier Returns</p>
          </a>
        </li>
        @endif

                @if(in_array(auth()->user()->role, ['owner', 'admin']))
        <li class="nav-item">
          <a href="{{ route('admin.supplier_content.index') }}"
             class="nav-link {{ request()->routeIs('admin.supplier_content.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-edit"></i>
            <p>Supplier Moderation</p>
          </a>
        </li>
        @endif

        <!-- STOCKS -->
        <li class="nav-header">INVENTORY</li>

        @if(in_array(auth()->user()->role, ['owner', 'admin', 'inspector']))
        <li class="nav-item">
          <a href="{{ url('/approved_good_stock') }}"
             class="nav-link {{ request()->is('approved_good_stock') ? 'active' : '' }}">
            <i class="nav-icon fas fa-check-circle"></i>
            <p>Stock Approval</p>
          </a>
        </li>
        @endif

        <li class="nav-item">
          <a href="{{ url('/stock_categorys') }}"
             class="nav-link {{ request()->is('stock_categorys') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tags"></i>
            <p>Inventory Categories</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ url('/product_stock_list') }}"
             class="nav-link {{ request()->is('product_stock_list') ? 'active' : '' }}">
            <i class="nav-icon fas fa-warehouse"></i>
            <p>Inventory Items</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ url('/stock_ledger') }}"
             class="nav-link {{ request()->is('stock_ledger') ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Inventory Ledger</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ url('/shelf_control') }}"
             class="nav-link {{ request()->is('shelf_control') ? 'active' : '' }}">
            <i class="nav-icon fas fa-th-large"></i>
            <p>Shelf Control</p>
          </a>
        </li>

        @if(in_array(auth()->user()->role, ['owner', 'admin', 'staff', 'accountant']))
        <!-- CUSTOMERS -->
        <li class="nav-header">SALES</li>

        @if(in_array(auth()->user()->role, ['owner', 'admin', 'staff']))
        <li class="nav-item">
          <a href="{{ url('/customers') }}"
             class="nav-link {{ request()->is('customers') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>Customers</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ url('/sales_order') }}"
             class="nav-link {{ request()->path() === 'sales_order' ? 'active' : '' }}">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>Sales Orders</p>
          </a>
        </li>
        @endif

        <li class="nav-item">
          <a href="{{ url('/sales_order_history') }}"
             class="nav-link {{ request()->path() === 'sales_order_history' ? 'active' : '' }}">
            <i class="nav-icon fas fa-history"></i>
            <p>Sales Invoices</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ url('/customer_payments') }}"
             class="nav-link {{ request()->is('customer_payments') ? 'active' : '' }}">
            <i class="nav-icon fas fa-money-bill-wave"></i>
            <p>Customer Payments</p>
          </a>
        </li>

        <!-- REPORTS -->
        <li class="nav-header">REPORTS</li>

        <li class="nav-item">
          <a href="{{ url('/sale_report') }}"
             class="nav-link {{ request()->is('sale_report') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>Sale Reports</p>
          </a>
        </li>

        @if(in_array(auth()->user()->role, ['owner', 'admin', 'accountant']))
        <li class="nav-item">
          <a href="{{ url('/profit_report') }}"
             class="nav-link {{ request()->is('profit_report') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>Profit Reports</p>
          </a>
        </li>
        @endif
        @endif
        @endif

        @if(auth()->user()->role === 'supplier')
        <li class="nav-header">SUPPLIER AREA</li>
        <li class="nav-item">
          <a href="{{ url('/Supplier_Dashboard') }}"
             class="nav-link {{ request()->is('Supplier_Dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>Supplier Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('supplier.orders.manage') }}"
             class="nav-link {{ request()->routeIs('supplier.orders.manage') ? 'active' : '' }}">
            <i class="nav-icon fas fa-shipping-fast"></i>
            <p>Manage Orders</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('Supplier_Dashboard.disputes') }}"
             class="nav-link {{ request()->routeIs('Supplier_Dashboard.disputes') ? 'active' : '' }}">
            <i class="nav-icon fas fa-exclamation-triangle"></i>
            <p>Disputes / Returns</p>
          </a>
        </li>
        @endif

      </ul>
    </nav>

  </div>
</aside>
