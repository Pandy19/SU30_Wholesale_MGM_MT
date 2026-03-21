@extends('backend.layouts.master')
@section('title', 'Sale Reports | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
    <!-- PAGE TITLE -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark">Sales Report</h1>
                    <p class="text-muted mb-0">Sales performance overview (B2B & B2C)</p>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Sale Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <section class="content">
        <div class="container-fluid">

            <!-- SUMMARY CARDS -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="info-box shadow-sm border-0">
                        <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted small text-uppercase font-weight-bold">Total Sales</span>
                            <span class="info-box-number h5 font-weight-bold mb-0">${{ number_format($totalSales, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-box shadow-sm border-0">
                        <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted small text-uppercase font-weight-bold">Total Orders</span>
                            <span class="info-box-number h5 font-weight-bold mb-0">{{ number_format($totalOrders) }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-box shadow-sm border-0">
                        <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted small text-uppercase font-weight-bold">B2B Revenue</span>
                            <span class="info-box-number h5 font-weight-bold mb-0">${{ number_format($b2bRevenue, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-box shadow-sm border-0">
                        <span class="info-box-icon bg-warning text-white"><i class="fas fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-muted small text-uppercase font-weight-bold">B2C Revenue</span>
                            <span class="info-box-number h5 font-weight-bold mb-0">${{ number_format($b2cRevenue, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FILTERS -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('sale_report.index') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted text-uppercase">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted text-uppercase">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted text-uppercase">Customer Type</label>
                                <select name="customer_type" class="form-control">
                                    <option value="">All Customer Types</option>
                                    <option value="B2B" {{ request('customer_type') == 'B2B' ? 'selected' : '' }}>B2B (Wholesale)</option>
                                    <option value="B2C" {{ request('customer_type') == 'B2C' ? 'selected' : '' }}>B2C (Retail)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary flex-fill shadow-sm">
                                        <i class="fas fa-filter mr-1"></i> Apply Filter
                                    </button>
                                    <a href="{{ route('sale_report.index') }}" class="btn btn-outline-secondary ml-2 shadow-sm">
                                        <i class="fas fa-redo mr-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MAIN TREND CHARTS -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <strong class="text-muted"><i class="fas fa-chart-line mr-1 text-primary"></i> Monthly Sales Trend</strong>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="salesTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <strong class="text-muted"><i class="fas fa-chart-pie mr-1 text-success"></i> Revenue vs Est. Cost</strong>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div style="height: 300px; width: 100%;">
                                <canvas id="profitCostChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CUSTOMER TYPE PROFITABILITY -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <strong class="text-muted"><i class="fas fa-balance-scale mr-1 text-info"></i> Customer Type Profitability (B2B vs B2C)</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div style="height: 300px;">
                                        <canvas id="profitabilityChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex flex-column justify-content-center">
                                    <div class="p-4 bg-light rounded mb-3 border">
                                        <h6 class="font-weight-bold text-primary">B2B (Wholesale)</h6>
                                        <div class="d-flex justify-content-between h6 mt-2">
                                            <span>Revenue:</span>
                                            <span class="font-weight-bold">${{ number_format($b2bRevenue, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between small text-muted">
                                            <span>Profit Margin:</span>
                                            <span class="font-weight-bold text-success">{{ $b2bRevenue > 0 ? round((($b2bRevenue - $b2bCost) / $b2bRevenue) * 100, 1) : 0 }}%</span>
                                        </div>
                                    </div>
                                    <div class="p-4 bg-light rounded border">
                                        <h6 class="font-weight-bold text-warning">B2C (Retail)</h6>
                                        <div class="d-flex justify-content-between h6 mt-2">
                                            <span>Revenue:</span>
                                            <span class="font-weight-bold">${{ number_format($b2cRevenue, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between small text-muted">
                                            <span>Profit Margin:</span>
                                            <span class="font-weight-bold text-success">{{ $b2cRevenue > 0 ? round((($b2cRevenue - $b2cCost) / $b2cRevenue) * 100, 1) : 0 }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SALES DETAILS TABLE -->
            <div class="card border-0 shadow-sm overflow-hidden mb-5">
                <div class="card-header bg-white py-3">
                    <strong class="text-muted"><i class="fas fa-table mr-1 text-info"></i> Recent Sales Details</strong>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light small text-uppercase">
                                <tr>
                                    <th class="pl-3 py-3 border-0">Invoice #</th>
                                    <th class="py-3 border-0">Date</th>
                                    <th class="py-3 border-0">Customer</th>
                                    <th class="py-3 border-0 text-center">Type</th>
                                    <th class="py-3 border-0">Payment</th>
                                    <th class="py-3 border-0 text-center">Status</th>
                                    <th class="py-3 border-0 text-right pr-3">Total</th>
                                    <th class="py-3 border-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales_details as $order)
                                <tr>
                                    <td class="pl-3 font-weight-bold align-middle">
                                        <span class="text-primary">{{ $order->order_number ?? 'INV-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td class="align-middle text-muted small">{{ date('d M Y', strtotime($order->order_date)) }}</td>
                                    <td class="align-middle"><div class="font-weight-bold text-dark">{{ $order->customer->name }}</div></td>
                                    <td class="text-center align-middle"><span class="badge badge-light border text-xs px-2">{{ $order->customer->type }}</span></td>
                                    <td class="align-middle"><span class="small text-muted"><i class="fas fa-credit-card mr-1"></i> {{ ucfirst($order->payment_method) }}</span></td>
                                    <td class="text-center align-middle">
                                        @php
                                            $statusClass = ['paid' => 'success', 'partial' => 'warning', 'unpaid' => 'danger'][$order->payment_status] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $statusClass }} px-3 py-1 text-uppercase" style="font-size: 10px;">{{ $order->payment_status }}</span>
                                    </td>
                                    <td class="text-right pr-3 align-middle font-weight-bold text-primary">${{ number_format($order->total_amount, 2) }}</td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-light border shadow-none" data-toggle="modal" data-target="#invoiceDetailModal{{ $order->id }}" title="View Details">
                                                <i class="fas fa-eye text-primary"></i>
                                            </button>
                                            <a href="{{ route('sales_order.confirm_sale', $order->id) }}" class="btn btn-sm btn-light border shadow-none ml-1" target="_blank" title="Print Invoice">
                                                <i class="fas fa-print text-secondary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center py-5 text-muted">No sales found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">Showing {{ $sales_details->firstItem() ?? 0 }} to {{ $sales_details->lastItem() ?? 0 }} of {{ $sales_details->total() }} entries</div>
                        <div>{{ $sales_details->appends(request()->query())->links('pagination::bootstrap-4') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    // 1. Monthly Sales Trend
    const trendCtx = document.getElementById('salesTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyTrend->pluck('month')) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode($monthlyTrend->pluck('revenue')) !!},
                backgroundColor: 'rgba(0, 123, 255, 0.05)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: 'rgba(0, 123, 255, 1)',
                pointBorderColor: '#fff',
                tension: 0.3
            }]
        },
        options: { maintainAspectRatio: false, legend: { display: false }, scales: { yAxes: [{ ticks: { beginAtZero: true, callback: function(v){return '$'+v.toLocaleString();} } }] } }
    });

    // 2. Revenue vs Cost
    const profitCtx = document.getElementById('profitCostChart').getContext('2d');
    new Chart(profitCtx, {
        type: 'doughnut',
        data: {
            labels: ['Total Cost', 'Est. Profit'],
            datasets: [{
                data: [{{ $totalCost }}, {{ max(0, $totalSales - $totalCost) }}],
                backgroundColor: ['#dc3545', '#28a745'],
                borderWidth: 2, borderColor: '#ffffff'
            }]
        },
        options: { maintainAspectRatio: false, cutoutPercentage: 75, legend: { position: 'bottom', labels: { boxWidth: 12, padding: 20, fontStyle: 'bold' } } }
    });

    // 3. Profitability (B2B vs B2C Grouped Bar)
    const profitColCtx = document.getElementById('profitabilityChart').getContext('2d');
    new Chart(profitColCtx, {
        type: 'bar',
        data: {
            labels: ['B2B (Wholesale)', 'B2C (Retail)'],
            datasets: [
                {
                    label: 'Revenue ($)',
                    data: [{{ $b2bRevenue }}, {{ $b2cRevenue }}],
                    backgroundColor: '#007bff'
                },
                {
                    label: 'Cost ($)',
                    data: [{{ $b2bCost }}, {{ $b2cCost }}],
                    backgroundColor: '#6c757d'
                },
                {
                    label: 'Profit ($)',
                    data: [{{ max(0, $b2bRevenue - $b2bCost) }}, {{ max(0, $b2cRevenue - $b2cCost) }}],
                    backgroundColor: '#28a745'
                }
            ]
        },
        options: { 
            maintainAspectRatio: false, 
            scales: { yAxes: [{ ticks: { beginAtZero: true, callback: function(v){return '$'+v.toLocaleString();} } }] },
            legend: { position: 'bottom', labels: { boxWidth: 12 } }
        }
    });
});
</script>
@endpush

@endsection
