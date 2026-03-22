@extends('backend.layouts.master')
@section('title', 'Dashboard | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
    <section class="content p-4">

        <div class="mb-3">
            <h3>Dashboard</h3>
            <p class="text-muted mb-0">Wholesale Management Overview</p>
        </div>

        <!-- TOP STATS BOXES -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $supplierCount }}</h3>
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
                        <h3>{{ $productCount }}</h3>
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
                        <h3>{{ $lowStockCount }}</h3>
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
                        <h3>${{ number_format($todaySales, 2) }}</h3>
                        <p>Today Sales</p>
                    </div>
                    <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                    <a href="{{ url('/sale_report') }}" class="small-box-footer">
                        Sales Report <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- LEFT COLUMN (Analytical) -->
            <div class="col-lg-6">
                <!-- SALES CHART -->
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Sales Performance</h3>
                            <a href="{{ url('/sale_report') }}">View Report</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="text-bold text-lg">${{ number_format($thisMonthSales, 2) }}</span>
                                <span>Sales This Month</span>
                            </p>
                            <p class="ml-auto d-flex flex-column text-right">
                                <span class="{{ $salesGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $salesGrowth >= 0 ? 'up' : 'down' }}"></i> {{ number_format(abs($salesGrowth), 1) }}%
                                </span>
                                <span class="text-muted">Since last month</span>
                            </p>
                        </div>
                        <div class="position-relative mb-4">
                            <canvas id="sales-chart" height="200"></canvas>
                        </div>
                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2">
                                <i class="fas fa-square text-primary"></i> This Month
                            </span>
                            <span>
                                <i class="fas fa-square text-gray"></i> Last Month
                            </span>
                        </div>
                    </div>
                </div>

                <!-- RECENT ACTIVITIES FEED -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Recent Activities Feed</strong></h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @foreach($unifiedActivities as $activity)
                                <li class="item" style="cursor: pointer;" 
                                    onclick="window.location='{{ $activity->activity_type == 'stock' ? url('/stock_ledger') : ($activity->activity_type == 'sale' ? url('/sales_order/confirm/'.$activity->id) : url('/approved_good_stock')) }}'">
                                    <div class="product-img">
                                        @if($activity->activity_type == 'stock')
                                            <div class="activity-icon bg-info rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-warehouse text-white"></i>
                                            </div>
                                        @elseif($activity->activity_type == 'sale')
                                            <div class="activity-icon bg-success rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-shopping-cart text-white"></i>
                                            </div>
                                        @elseif($activity->activity_type == 'purchase')
                                            <div class="activity-icon bg-warning rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-truck text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-info ml-2">
                                        @if($activity->activity_type == 'stock')
                                            <span class="product-title text-primary">
                                                Stock {{ ucfirst($activity->type) }}: {{ $activity->product->name ?? 'Product' }}
                                                @php
                                                    $isStockIn = in_array($activity->type, ['Stock In', 'Initial Stock', 'in']);
                                                @endphp
                                                <span class="badge {{ $isStockIn ? 'badge-success' : 'badge-danger' }} float-right">
                                                    {{ $isStockIn ? '+' : '-' }}{{ $activity->quantity }}
                                                </span>
                                            </span>
                                            <span class="product-description text-xs">{{ $activity->created_at->diffForHumans() }}</span>

                                        @elseif($activity->activity_type == 'sale')
                                            <span class="product-title text-success">
                                                New Order #{{ $activity->order_number }}
                                                <span class="badge badge-primary float-right">${{ number_format($activity->total_amount, 2) }}</span>
                                            </span>
                                            <span class="product-description text-xs">{{ $activity->customer->name ?? 'Retailer' }} | {{ $activity->created_at->diffForHumans() }}</span>

                                        @elseif($activity->activity_type == 'purchase')
                                            <span class="product-title text-warning">
                                                PO #{{ $activity->po_number }}
                                                <span class="badge badge-warning float-right">{{ ucfirst($activity->status) }}</span>
                                            </span>
                                            <span class="product-description text-xs">{{ $activity->supplier->name ?? 'Supplier' }} | Arrival expected</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                            @if($unifiedActivities->isEmpty())
                                <li class="item p-3 text-center text-muted">No recent activities found</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN (Operational) -->
            <div class="col-lg-6">
                <!-- STOCK COMPARISON CHART -->
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Stock Inventory Status</h3>
                            <a href="{{ url('/stock_ledger') }}">View Report</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="text-bold text-lg">{{ number_format($currentTotalStock) }}</span>
                                <span>Total Units in Warehouse</span>
                            </p>
                            <p class="ml-auto d-flex flex-column text-right">
                                <span class="{{ $stockGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $stockGrowth >= 0 ? 'up' : 'down' }}"></i> {{ number_format(abs($stockGrowth), 1) }}%
                                </span>
                                <span class="text-muted">Since last month</span>
                            </p>
                        </div>
                        <div class="position-relative mb-4">
                            <canvas id="stock-chart" height="200"></canvas>
                        </div>
                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2">
                                <i class="fas fa-square text-primary"></i> This Month
                            </span>
                            <span>
                                <i class="fas fa-square text-gray"></i> Last Month
                            </span>
                        </div>
                    </div>
                </div>

                <!-- QUICK OPERATIONS -->
                <div class="card">
                    <div class="card-header">
                        <strong>Quick Operations</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <a href="{{ url('/goods_receiving') }}" class="btn btn-block btn-outline-primary text-left">
                                    <i class="fas fa-truck-loading mr-2"></i> Receive Goods
                                </a>
                            </div>
                            <div class="col-6 mb-2">
                                <a href="{{ url('/sales_order') }}" class="btn btn-block btn-outline-success text-left">
                                    <i class="fas fa-shopping-cart mr-2"></i> New Sale Order
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ url('/customer_payments') }}" class="btn btn-block btn-outline-info text-left">
                                    <i class="fas fa-money-bill-wave mr-2"></i> Receive Payment
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ url('/suppliers') }}" class="btn btn-block btn-outline-secondary text-left">
                                    <i class="fas fa-user-plus mr-2"></i> New Supplier
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LATEST SALES ORDERS (Moved here) -->
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title"><strong>Latest Sales Orders</strong></h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Delivery</th>
                                        <th>Payment</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestOrders as $order)
                                        <tr>
                                            <td><a href="{{ url('/sales_order/confirm/'.$order->id) }}">#{{ $order->order_number }}</a></td>
                                            <td>{{ $order->customer->name ?? 'Retailer' }}</td>
                                            <td>
                                                <span class="badge {{ $order->status == 'completed' ? 'badge-success' : ($order->status == 'pending' ? 'badge-warning' : 'badge-info') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $paymentClass = [
                                                        'paid' => 'success',
                                                        'partial' => 'warning',
                                                        'unpaid' => 'danger'
                                                    ][$order->payment_status] ?? 'secondary';
                                                @endphp
                                                <span class="badge badge-{{ $paymentClass }}">
                                                    {{ strtoupper($order->payment_status) }}
                                                </span>
                                            </td>
                                            <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(function () {
  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

  // SALES CHART
  var $salesChart = $('#sales-chart')
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: ['Current Month', 'Last Month'],
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor: '#007bff',
          data: [{{ $thisMonthSales }}, {{ $lastMonthSales }}]
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .05)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }
              return '$' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })

  // STOCK CHART
  var $stockChart = $('#stock-chart')
  var stockChart = new Chart($stockChart, {
    type: 'line',
    data: {
      labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
      datasets: [
        {
          type: 'line',
          data: [0, 0, 0, {{ $thisMonthStock }}],
          backgroundColor: 'transparent',
          borderColor: '#007bff',
          pointBorderColor: '#007bff',
          pointBackgroundColor: '#007bff',
          fill: false
        },
        {
          type: 'line',
          data: [0, 0, 0, {{ $lastMonthStock }}],
          backgroundColor: 'tansparent',
          borderColor: '#ced4da',
          pointBorderColor: '#ced4da',
          pointBackgroundColor: '#ced4da',
          fill: false
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .05)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 100
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
})
</script>
@endpush
