@extends('backend.layouts.master')
@section('title', 'Profit Reports | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- ===================================================== -->
<!-- PAGE TITLE -->
<!-- ===================================================== -->
<div class="mb-3">
    <h3>Profit Report</h3>
    <p class="text-muted mb-0">
        Profit analysis by category, brand, and product
    </p>
</div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Profit</span>
                <span class="info-box-number">$32,850</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-coins"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Cost</span>
                <span class="info-box-number">$95,600</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Revenue</span>
                <span class="info-box-number">$128,450</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-percentage"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Avg Profit Margin</span>
                <span class="info-box-number">27%</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- CHARTS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <!-- PROFIT MARGIN (IMPROVED) -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>Profit Margin (%) by Product</strong>
            </div>
            <div class="card-body">
                <canvas id="profitMarginChart" height="180"></canvas>
            </div>
        </div>
    </div>

    <!-- PROFIT PER MONTH -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>Profit Per Month</strong>
            </div>
            <div class="card-body">
                <canvas id="profitMonthChart" height="180"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- PROFIT TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-header">
    <strong>Profit Details (Category / Brand / Product)</strong>
</div>

<div class="card-body p-0">
<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>Category</th>
    <th>Brand</th>
    <th>Product</th>
    <th>Cost</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Profit</th>
    <th>Margin %</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

<tr>
    <td>Mobile</td>
    <td>Samsung</td>
    <td>Galaxy S24</td>
    <td>$1,500</td>
    <td>$1,880</td>
    <td>5</td>
    <td class="text-success">$1,900</td>
    <td>25%</td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#profitDetailModal">
            View
        </button>
    </td>
</tr>

<tr>
    <td>Mobile</td>
    <td>Apple</td>
    <td>iPhone 15 Pro</td>
    <td>$1,200</td>
    <td>$1,450</td>
    <td>8</td>
    <td class="text-success">$2,000</td>
    <td>21%</td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#profitDetailModal">
            View
        </button>
    </td>
</tr>

<tr>
    <td>Laptop</td>
    <td>Dell</td>
    <td>XPS 15</td>
    <td>$1,600</td>
    <td>$1,950</td>
    <td>3</td>
    <td class="text-success">$1,050</td>
    <td>22%</td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#profitDetailModal">
            View
        </button>
    </td>
</tr>

</tbody>
</table>
</div>

<!-- ===================================================== -->
<!-- PAGINATION (ADMINLTE STYLE) -->
<!-- ===================================================== -->
<div class="card-footer clearfix">
<ul class="pagination pagination-sm m-0 float-right">
    <li class="page-item disabled"><a class="page-link">«</a></li>
    <li class="page-item active"><a class="page-link">1</a></li>
    <li class="page-item"><a class="page-link">2</a></li>
    <li class="page-item"><a class="page-link">3</a></li>
    <li class="page-item"><a class="page-link">»</a></li>
</ul>
</div>

</div>

<!-- ===================================================== -->
<!-- POPUP VIEW (UNCHANGED - ALREADY GOOD) -->
<!-- ===================================================== -->
@includeIf('backend.partials.profit-detail-modal')

</section>
</div>


<!-- ===================================================== -->
<!-- IMPROVED PROFIT DETAIL MODAL -->
<!-- ===================================================== -->
<div class="modal fade" id="profitDetailModal" tabindex="-1">
<div class="modal-dialog modal-xl">
<div class="modal-content">

<!-- HEADER -->
<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="fas fa-box-open mr-2"></i> Product Profit Overview
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
</div>

<!-- BODY -->
<div class="modal-body">

<div class="row">

    <!-- LEFT: PRODUCT INFO -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <img src="{{ asset('storage/products/galaxy-s24.png') }}" class="img-fluid mb-3" style="max-height:180px">
                <h5 class="mb-1">Galaxy S24</h5>
                <p class="text-muted mb-2">Samsung · Mobile</p>

                <span class="badge badge-success p-2">
                    Profit Margin: 25%
                </span>
            </div>
        </div>
    </div>

    <!-- RIGHT: FINANCIAL DETAILS -->
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-body">

                <h6 class="mb-3"><i class="fas fa-receipt mr-2"></i>Financial Breakdown</h6>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="small text-muted">Purchase Cost</div>
                        <h5 class="text-danger">$1,500</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Selling Price</div>
                        <h5 class="text-info">$1,880</h5>
                    </div>
                </div>

                <hr>

                <table class="table table-sm">
                    <tr>
                        <th width="40%">Quantity Sold</th>
                        <td>5</td>
                    </tr>
                    <tr>
                        <th>Total Cost</th>
                        <td>$7,500</td>
                    </tr>
                    <tr>
                        <th>Total Revenue</th>
                        <td>$9,400</td>
                    </tr>
                    <tr class="bg-light">
                        <th>Total Profit</th>
                        <td class="text-success font-weight-bold">$1,900</td>
                    </tr>
                </table>

            </div>
        </div>
    </div>

</div>

</div>

@endsection
