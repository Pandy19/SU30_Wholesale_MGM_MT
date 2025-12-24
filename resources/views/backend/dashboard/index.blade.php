@extends('backend.layouts.master')
@section('title', 'Dashboard | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- ===================================================== -->
<!-- PAGE TITLE -->
<!-- ===================================================== -->
<div class="mb-3">
    <h3>Dashboard</h3>
    <p class="text-muted mb-0">Wholesale Management Overview</p>
</div>

<!-- ===================================================== -->
<!-- TOP KPI ROW -->
<!-- ===================================================== -->
<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>12</h3>
                <p>Brand Suppliers</p>
            </div>
            <div class="icon"><i class="fas fa-industry"></i></div>
            <a href="{{ url('/suppliers') }}" class="small-box-footer">
                View Suppliers <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>248</h3>
                <p>Products</p>
            </div>
            <div class="icon"><i class="fas fa-boxes"></i></div>
            <a href="{{ url('/product_management') }}" class="small-box-footer">
                Manage Products <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>18</h3>
                <p>Low Stock Alerts</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <a href="{{ url('/product_stock_list') }}" class="small-box-footer">
                Check Stocks <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>$12,850</h3>
                <p>Today Profit</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <a href="{{ url('/profit_report') }}" class="small-box-footer">
                Profit Report <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- SECOND ROW -->
<!-- ===================================================== -->
<div class="row">

    <!-- LEFT SIDE -->
    <div class="col-md-8">

        <!-- SALES & PROFIT CHART -->
        <div class="card">
            <div class="card-header">
                <strong>Sales & Profit Overview</strong>
            </div>
            <div class="card-body">
                <canvas id="dashboardSalesChart" height="130"></canvas>
            </div>
        </div>


    </div>

    <!-- RIGHT SIDE -->
    <div class="col-md-4">

        <!-- QUICK ACTIONS -->
        <div class="card">
            <div class="card-header">
                <strong>Quick Actions</strong>
            </div>
            <div class="card-body">

                <a href="{{ url('/goods_receiving') }}" class="btn btn-block btn-outline-primary mb-2">
                    <i class="fas fa-truck-loading mr-1"></i> Receive Goods
                </a>

                <a href="{{ url('/sales_order') }}" class="btn btn-block btn-outline-success mb-2">
                    <i class="fas fa-shopping-cart mr-1"></i> New Sale Order
                </a>

                <a href="{{ url('/customer_payments') }}" class="btn btn-block btn-outline-info mb-2">
                    <i class="fas fa-money-bill-wave mr-1"></i> Receive Payment
                </a>

                <a href="{{ url('/profit_report') }}" class="btn btn-block btn-outline-dark">
                    <i class="fas fa-chart-line mr-1"></i> View Profit Report
                </a>

            </div>
        </div>

        <!-- CUSTOMER SUMMARY (IMPROVED WITH ICONS) -->
        <div class="card">
            <div class="card-header">
                <strong>Customers</strong>
            </div>
            <div class="card-body">

                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-users fa-2x text-primary mr-3"></i>
                    <div>
                        <small class="text-muted">Total Customers</small>
                        <h5 class="mb-0">145</h5>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-user-check fa-2x text-success mr-3"></i>
                    <div>
                        <small class="text-muted">Active This Month</small>
                        <h5 class="mb-0">38</h5>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <i class="fas fa-file-invoice-dollar fa-2x text-danger mr-3"></i>
                    <div>
                        <small class="text-muted">Outstanding Payments</small>
                        <h5 class="mb-0 text-danger">$9,200</h5>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

</section>
</div>

@endsection
