@extends('backend.layouts.master')
@section('title', 'Sale Reports | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content">

<!-- ===================================================== -->
<!-- PAGE TITLE -->
<!-- ===================================================== -->
<div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sales Report</h1>
                    <p class="text-muted mb-0">
                     Sales performance overview (B2B & B2C)
                    </p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Report</a></li>
                        <li class="breadcrumb-item active">Sale Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Sales</span>
                <span class="info-box-number">$128,450</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fas fa-shopping-cart"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Orders</span>
                <span class="info-box-number">214</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary">
                <i class="fas fa-users"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">B2B Revenue</span>
                <span class="info-box-number">$82,300</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-user"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">B2C Revenue</span>
                <span class="info-box-number">$46,150</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-4">
<div class="card-body">
<div class="row">

    <div class="col-md-3">
        <input type="date" class="form-control">
    </div>

    <div class="col-md-3">
        <input type="date" class="form-control">
    </div>

    <div class="col-md-3">
        <select class="form-control">
            <option>All Customer Types</option>
            <option>B2B</option>
            <option>B2C</option>
        </select>
    </div>

    <div class="col-md-3 text-right">
        <button class="btn btn-primary">Apply</button>
        <button class="btn btn-outline-secondary ml-1">Reset</button>
    </div>

</div>
</div>
</div>

<!-- ===================================================== -->
<!-- CHARTS ROW -->
<!-- ===================================================== -->
<div class="row mb-4">

    <!-- MONTHLY SALES TREND -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>Monthly Sales Trend</strong>
            </div>
            <div class="card-body">
                <canvas id="salesTrendChart" height="140"></canvas>
            </div>
        </div>
    </div>

    <!-- PROFIT VS COST -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>Profit vs Cost</strong>
            </div>
            <div class="card-body">
                <canvas id="profitCostChart" height="140"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- SALES DETAILS TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-header">
    <strong>Sales Details</strong>
</div>

<div class="card-body p-0">
<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>Invoice No</th>
    <th>Date</th>
    <th>Customer</th>
    <th>Type</th>
    <th>Payment</th>
    <th>Status</th>
    <th>Total</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

<tr>
    <td>SI-00021</td>
    <td>2025-01-18</td>
    <td>ABC Mobile Shop</td>
    <td><span class="badge badge-primary">B2B</span></td>
    <td>Bank Transfer</td>
    <td><span class="badge badge-success">Paid</span></td>
    <td>$12,800</td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-primary"
                data-toggle="modal"
                data-target="#invoiceDetailModal">
            View
        </button>
        <button class="btn btn-sm btn-outline-secondary ml-1"
                onclick="printInvoice()">
            Print
        </button>
    </td>
</tr>

<tr>
    <td>SI-00023</td>
    <td>2025-01-20</td>
    <td>Tech Partner Co.</td>
    <td><span class="badge badge-primary">B2B</span></td>
    <td>Net 7 Days</td>
    <td><span class="badge badge-warning">Partial</span></td>
    <td>$9,400</td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-primary"
                data-toggle="modal"
                data-target="#invoiceDetailModal">
            View
        </button>
        <button class="btn btn-sm btn-outline-secondary ml-1"
                onclick="printInvoice()">
            Print
        </button>
    </td>
</tr>

</tbody>
</table>
</div>
</div>

<!-- ===================================================== -->
<!-- INVOICE DETAIL MODAL -->
<!-- ===================================================== -->
<div class="modal fade" id="invoiceDetailModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content" id="printArea">

<div class="modal-header">
    <h5 class="modal-title">Invoice Details</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

    <h6>Invoice No: SI-00023</h6>
    <p class="text-muted mb-2">
        Customer: Tech Partner Co. (B2B)
    </p>

    <span class="badge badge-warning mb-2">Partial Payment</span>

    <p class="small text-muted">
        Reason: 50% paid upfront. Remaining balance due within Net 7 days.
    </p>

    <table class="table table-sm table-bordered mt-3">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Samsung Galaxy S24</td>
                <td>5</td>
                <td>$1,880</td>
                <td>$9,400</td>
            </tr>
        </tbody>
    </table>

    <table class="table table-sm">
        <tr><th>Payment Method</th><td>Bank Transfer</td></tr>
        <tr><th>Payment Terms</th><td>Net 7 Days</td></tr>
        <tr><th>Paid Amount</th><td>$4,700</td></tr>
        <tr><th>Outstanding</th><td class="text-danger">$4,700</td></tr>
    </table>

</div>

<div class="modal-footer">
    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

</div>
</div>
</div>


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

</section>
</div>

@endsection
