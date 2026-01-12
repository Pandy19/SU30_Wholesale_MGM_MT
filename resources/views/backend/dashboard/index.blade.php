@extends('backend.layouts.master')
@section('title', 'Dashboard | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
    <section class="content p-4">

        <div class="mb-3">
            <h3>Dashboard</h3>
            <p class="text-muted mb-0">Wholesale Management Overview</p>
        </div>

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

        <div class="row">

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <strong>Sales & Profit Overview</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="dashboardSalesChart" height="130"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Quick Actions</strong>
                    </div>
                    <div class="card-body">
                        <a href="{{ url('/goods_receiving') }}" class="btn btn-block btn-outline-primary mb-2 text-left">
                            <i class="fas fa-truck-loading mr-2"></i> Receive Goods
                        </a>
                        <a href="{{ url('/sales_order') }}" class="btn btn-block btn-outline-success mb-2 text-left">
                            <i class="fas fa-shopping-cart mr-2"></i> New Sale Order
                        </a>
                        <a href="{{ url('/customer_payments') }}" class="btn btn-block btn-outline-info mb-2 text-left">
                            <i class="fas fa-money-bill-wave mr-2"></i> Receive Payment
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Outstanding Payments</span>
                            <span class="text-danger font-weight-bold">$9,200</span>
                        </div>
                        <div class="progress progress-xxs mt-2">
                            <div class="progress-bar bg-danger" style="width: 70%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Recent Activities</strong></h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            <li class="item">
                                <div class="product-info ml-2">
                                    <a href="javascript:void(0)" class="product-title text-primary">Order #SO-442 <span class="badge badge-success float-right">Delivered</span></a>
                                    <span class="product-description text-xs">Customer: Global Retail | 2 mins ago</span>
                                </div>
                            </li>
                            <li class="item">
                                <div class="product-info ml-2">
                                    <a href="javascript:void(0)" class="product-title text-info">Stock In: SKU-99 <span class="badge badge-warning float-right">Pending Approval</span></a>
                                    <span class="product-description text-xs">Received 500 units from Samsung | 1 hour ago</span>
                                </div>
                            </li>
                            <li class="item">
                                <div class="product-info ml-2">
                                    <a href="javascript:void(0)" class="product-title text-danger">Return: RT-112 <span class="badge badge-danger float-right">Refunded</span></a>
                                    <span class="product-description text-xs">5 defective units returned to Nike | 4 hours ago</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="small text-muted">View all activities</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title"><strong>New Supplier Registrations</strong></h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>Supplier/Company</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border: 1px solid #007bff;">
                                                    <small class="text-primary font-weight-bold" style="font-size: 8px;">MMO</small>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold" style="font-size: 13px;">John Doe</div>
                                                    <div class="text-muted" style="font-size: 11px;">TechWorld Corp</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><small>john@tech.com</small></td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td>
                                            <a href="#" class="btn btn-xs btn-primary"><i class="fas fa-eye"></i></a>
                                            <a href="#" class="btn btn-xs btn-success"><i class="fas fa-check"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border: 1px solid #007bff;">
                                                    <small class="text-primary font-weight-bold" style="font-size: 8px;">MMO</small>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold" style="font-size: 13px;">Sarah Smith</div>
                                                    <div class="text-muted" style="font-size: 11px;">Fashion Hub</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><small>sarah@hub.com</small></td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td>
                                            <a href="#" class="btn btn-xs btn-primary"><i class="fas fa-eye"></i></a>
                                            <a href="#" class="btn btn-xs btn-success"><i class="fas fa-check"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ url('/suppliers') }}" class="small text-muted">View All Suppliers</a>
                    </div>
                </div>
            </div>

        </div>

    </section>
</div>

@endsection