@extends('backend.layouts.master')
@section('title', 'Profit Reports | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
    <!-- PAGE TITLE -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark">Profit Analysis</h1>
                    <p class="text-muted mb-0">Financial performance & profitability dashboard</p>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profit Report</li>
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
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success shadow-sm border-0">
                        <div class="inner">
                            <p class="mb-1">Total Net Profit</p>
                            <h3 class="font-weight-bold">${{ number_format($totalProfit, 2) }}</h3>
                        </div>
                        <div class="icon text-white-50"><i class="fas fa-chart-line"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger shadow-sm border-0">
                        <div class="inner">
                            <p class="mb-1">Total Cost (COGS)</p>
                            <h3 class="font-weight-bold">${{ number_format($totalCost, 2) }}</h3>
                        </div>
                        <div class="icon text-white-50"><i class="fas fa-coins"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info shadow-sm border-0">
                        <div class="inner">
                            <p class="mb-1">Total Revenue</p>
                            <h3 class="font-weight-bold">${{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                        <div class="icon text-white-50"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning shadow-sm border-0">
                        <div class="inner">
                            <p class="mb-1 text-white">Avg. Profit Margin</p>
                            <h3 class="font-weight-bold text-white">{{ number_format($avgMargin, 1) }}%</h3>
                        </div>
                        <div class="icon text-white-50"><i class="fas fa-percentage"></i></div>
                    </div>
                </div>
            </div>

            <!-- FILTERS -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <strong class="text-muted"><i class="fas fa-filter mr-1 text-primary"></i> Data Filters</strong>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <form action="{{ route('profit_report.index') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-2">
                                <label class="small font-weight-bold text-muted text-uppercase">From</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="small font-weight-bold text-muted text-uppercase">To</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted text-uppercase">Category</label>
                                <select name="category_id" class="form-control">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted text-uppercase">Brand</label>
                                <select name="brand_id" class="form-control">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $br)
                                        <option value="{{ $br->id }}" {{ request('brand_id') == $br->id ? 'selected' : '' }}>{{ $br->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex mt-2 mt-md-0">
                                    <button type="submit" class="btn btn-primary flex-fill mr-1 shadow-sm">Apply</button>
                                    <a href="{{ route('profit_report.index') }}" class="btn btn-outline-secondary shadow-sm">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- CHARTS ROW -->
            <div class="row mb-4">
                <!-- PROFIT BY CATEGORY (DOUGHNUT) -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <strong class="text-muted"><i class="fas fa-chart-pie mr-1 text-success"></i> Profit by Category</strong>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <div style="height: 250px; width: 100%;">
                                <canvas id="categoryProfitChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PROFIT TREND (AREA CHART) -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <strong class="text-muted"><i class="fas fa-chart-area mr-1 text-info"></i> Monthly Profit Trend</strong>
                        </div>
                        <div class="card-body">
                            <div style="height: 250px;">
                                <canvas id="profitTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PROFIT DETAILS TABLE -->
            <div class="card border-0 shadow-sm overflow-hidden mb-5">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <strong class="text-muted"><i class="fas fa-table mr-1 text-dark"></i> Product Profitability Table</strong>
                    <button class="btn btn-sm btn-outline-success border-0 px-3" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i> Print Report
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light small text-uppercase">
                                <tr>
                                    <th class="pl-3 py-3 border-0">Category / Brand</th>
                                    <th class="py-3 border-0">Product Name</th>
                                    <th class="py-3 border-0 text-right">Avg. Cost</th>
                                    <th class="py-3 border-0 text-right">Avg. Price</th>
                                    <th class="py-3 border-0 text-center">Sales Qty</th>
                                    <th class="py-3 border-0 text-right">Net Profit</th>
                                    <th class="py-3 border-0 text-center">Margin %</th>
                                    <th class="py-3 border-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($profit_details as $index => $item)
                                <tr>
                                    <td class="pl-3 align-middle">
                                        <div class="small font-weight-bold">{{ $item->category_name }}</div>
                                        <div class="text-muted text-xs">{{ $item->brand_name }}</div>
                                    </td>
                                    <td class="align-middle font-weight-bold text-dark">{{ $item->product_name }}</td>
                                    <td class="text-right align-middle text-danger small">${{ number_format($item->cost_per_unit, 2) }}</td>
                                    <td class="text-right align-middle text-primary small">${{ number_format($item->avg_price, 2) }}</td>
                                    <td class="text-center align-middle"><span class="badge badge-light border">{{ number_format($item->total_qty) }}</span></td>
                                    <td class="text-right align-middle font-weight-bold text-success">
                                        ${{ number_format($item->total_profit, 2) }}
                                    </td>
                                    <td class="text-center align-middle" style="min-width: 140px;">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="progress progress-xxs mr-2 flex-grow-1" style="height: 4px; max-width: 60px;">
                                                <div class="progress-bar bg-{{ $item->margin_pct >= 25 ? 'success' : ($item->margin_pct >= 10 ? 'warning' : 'danger') }}" 
                                                     style="width: {{ min(100, $item->margin_pct) }}%"></div>
                                            </div>
                                            <span class="font-weight-bold small">{{ number_format($item->margin_pct, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-sm btn-light border shadow-none" 
                                                data-toggle="modal" 
                                                data-target="#detailModal{{ $index }}">
                                            <i class="fas fa-eye text-primary"></i>
                                        </button>

                                        <!-- DETAIL MODAL -->
                                        <div class="modal fade text-left" id="detailModal{{ $index }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content border-0 shadow-lg">
                                                    <div class="modal-header bg-dark text-white border-0">
                                                        <h5 class="modal-title font-weight-bold">{{ $item->product_name }} - Profit Detail</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body p-4 bg-light">
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                <div class="card h-100 border-0 shadow-sm">
                                                                    <div class="card-body text-center p-4">
                                                                        <div class="text-muted small text-uppercase mb-2 font-weight-bold">Profit Contribution</div>
                                                                        <div class="h2 font-weight-bold text-success mb-0">${{ number_format($item->total_profit, 2) }}</div>
                                                                        <div class="badge badge-success border px-3 py-2 mt-3">{{ number_format($item->margin_pct, 1) }}% Margin</div>
                                                                        <hr>
                                                                        <div class="text-muted small">Category: <strong>{{ $item->category_name }}</strong></div>
                                                                        <div class="text-muted small">Brand: <strong>{{ $item->brand_name }}</strong></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-7">
                                                                <div class="card border-0 shadow-sm h-100">
                                                                    <div class="card-body">
                                                                        <table class="table table-sm table-borderless">
                                                                            <tr class="border-bottom"><td class="py-2 text-muted">Total Sales Qty</td><td class="py-2 text-right font-weight-bold text-dark">{{ $item->total_qty }}</td></tr>
                                                                            <tr class="border-bottom"><td class="py-2 text-muted">Total Revenue</td><td class="py-2 text-right font-weight-bold text-primary">${{ number_format($item->total_revenue, 2) }}</td></tr>
                                                                            <tr class="border-bottom"><td class="py-2 text-muted">Total Cost (COGS)</td><td class="py-2 text-right font-weight-bold text-danger">${{ number_format($item->total_cost, 2) }}</td></tr>
                                                                            <tr class="bg-light h5 mb-0"><td class="py-3 font-weight-bold">Net Margin</td><td class="py-3 text-right font-weight-bold text-success">${{ number_format($item->total_profit, 2) }}</td></tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center py-5 text-muted"><i class="fas fa-search fa-2x mb-2 d-block opacity-25"></i> No data matches your criteria.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
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
    // 1. Profit by Category (Doughnut)
    const catCtx = document.getElementById('categoryProfitChart').getContext('2d');
    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryProfit->keys()) !!},
            datasets: [{
                data: {!! json_encode($categoryProfit->values()) !!},
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6610f2', '#e83e8c'],
                borderWidth: 2, borderColor: '#ffffff'
            }]
        },
        options: {
            maintainAspectRatio: false, cutoutPercentage: 70,
            legend: { position: 'right', labels: { boxWidth: 12, padding: 15, fontStyle: 'bold' } },
            tooltips: { callbacks: { label: function(it, da){ return da.labels[it.index]+': $'+da.datasets[0].data[it.index].toLocaleString(); } } }
        }
    });

    // 2. Profit Trend Chart (Area Line)
    const trendCtx = document.getElementById('profitTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyTrend->pluck('month')) !!},
            datasets: [{
                label: 'Monthly Profit ($)',
                data: {!! json_encode($monthlyTrend->pluck('profit')) !!},
                backgroundColor: 'rgba(23, 162, 184, 0.1)',
                borderColor: '#17a2b8',
                borderWidth: 3,
                pointRadius: 5,
                pointBackgroundColor: '#17a2b8',
                tension: 0.3, fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                yAxes: [{ gridLines: { color: 'rgba(0,0,0,0.05)' }, ticks: { beginAtZero: true, callback: function(v){return '$'+v.toLocaleString();} } }],
                xAxes: [{ gridLines: { display: false } }]
            }
        }
    });
});
</script>
@endpush

@endsection
