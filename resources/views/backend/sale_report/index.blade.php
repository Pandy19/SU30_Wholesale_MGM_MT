@extends('backend.layouts.master')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- ===================================================== -->
<!-- PAGE TITLE -->
<!-- ===================================================== -->
<div class="mb-3">
    <h3>Sales Report</h3>
    <p class="text-muted mb-0">
        Sales performance overview (B2B & B2C)
    </p>
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
<!-- CHART PLACEHOLDER -->
<!-- ===================================================== -->
<div class="card mb-4">
<div class="card-header">
    <strong>Monthly Sales Trend</strong>
</div>
<div class="card-body text-center text-muted">
    Chart will display here (Chart.js integration later)
    <div style="height:260px;border:1px dashed #ccc;"></div>
</div>
</div>

<!-- ===================================================== -->
<!-- SALES DETAILS TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-header d-flex justify-content-between">
    <strong>Sales Details</strong>
    <div>
        <button class="btn btn-sm btn-outline-primary">Export PDF</button>
        <button class="btn btn-sm btn-outline-success ml-1">Export Excel</button>
    </div>
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
        <button class="btn btn-sm btn-outline-primary">View</button>
        <button class="btn btn-sm btn-outline-secondary ml-1">Print</button>
    </td>
</tr>

<tr>
    <td>SI-00022</td>
    <td>2025-01-19</td>
    <td>Walk-in Customer</td>
    <td><span class="badge badge-warning">B2C</span></td>
    <td>Cash</td>
    <td><span class="badge badge-success">Paid</span></td>
    <td>$1,800</td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-primary">View</button>
        <button class="btn btn-sm btn-outline-secondary ml-1">Print</button>
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
        <button class="btn btn-sm btn-outline-primary">View</button>
        <button class="btn btn-sm btn-outline-secondary ml-1">Print</button>
    </td>
</tr>

</tbody>
</table>
</div>

<!-- PAGINATION -->
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
