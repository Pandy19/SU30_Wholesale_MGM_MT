@extends('backend.layouts.master')
@section('title', 'Sale Order Historys | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- ===================================================== -->
<!-- PAGE TITLE -->
<!-- ===================================================== -->
<div class="mb-3">
    <h3>Sales Order History</h3>
    <p class="text-muted mb-0">
        Complete list of sales orders and invoices
    </p>
</div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fas fa-file-invoice"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Sales</span>
                <span class="info-box-number">18</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Revenue</span>
                <span class="info-box-number">$52,300</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-clock"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Pending Payment</span>
                <span class="info-box-number">3</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-secondary">
                <i class="fas fa-users"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">B2B Customers</span>
                <span class="info-box-number">7</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-3">
<div class="card-body">
<div class="row">

    <div class="col-md-3">
        <input type="text" class="form-control"
               placeholder="Search Invoice / Order No">
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Customers</option>
            <option>Walk-in Customer</option>
            <option>ABC Mobile Shop</option>
            <option>Tech Partner Co.</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Types</option>
            <option>B2C</option>
            <option>B2B</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Payment Status</option>
            <option>Paid</option>
            <option>Unpaid</option>
            <option>Partial</option>
        </select>
    </div>

    <div class="col-md-2">
        <input type="date" class="form-control">
    </div>

    <div class="col-md-1 text-right">
        <button class="btn btn-outline-secondary w-100">
            Reset
        </button>
    </div>

</div>
</div>
</div>

<!-- ===================================================== -->
<!-- SALES ORDER TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>Invoice No</th>
    <th>Order Ref</th>
    <th>Customer</th>
    <th>Type</th>
    <th>Order Date</th>
    <th>Total</th>
    <th>Payment</th>
    <th>Created By</th>
    <th class="text-center">Action</th>
</tr>
</thead>

<tbody>

<tr>
    <td>SI-0001</td>
    <td>SO-0001</td>
    <td>Walk-in Customer</td>
    <td><span class="badge badge-info">B2C</span></td>
    <td>2025-01-18</td>
    <td>$3,300</td>
    <td><span class="badge badge-success">Paid</span></td>
    <td>Admin</td>
    <td class="text-center">
        <a href="{{ route('sales_order.confirm_sale') }}"
           class="btn btn-sm btn-primary">
            View Invoice
        </a>
    </td>
</tr>

<tr>
    <td>SI-0002</td>
    <td>SO-0002</td>
    <td>ABC Mobile Shop</td>
    <td><span class="badge badge-warning">B2B</span></td>
    <td>2025-01-19</td>
    <td>$12,400</td>
    <td><span class="badge badge-warning">Unpaid</span></td>
    <td>Staff</td>
    <td class="text-center">
        <a href="{{ route('sales_order.confirm_sale') }}"
           class="btn btn-sm btn-primary">
            View Invoice
        </a>
    </td>
</tr>

<tr>
    <td>SI-0003</td>
    <td>SO-0003</td>
    <td>Tech Partner Co.</td>
    <td><span class="badge badge-warning">B2B</span></td>
    <td>2025-01-20</td>
    <td>$8,700</td>
    <td><span class="badge badge-success">Paid</span></td>
    <td>Admin</td>
    <td class="text-center">
        <a href="{{ route('sales_order.confirm_sale') }}"
           class="btn btn-sm btn-primary">
            View Invoice
        </a>
    </td>
</tr>

</tbody>
</table>

</div>

<!-- ===================================================== -->
<!-- PAGINATION -->
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

</section>
</div>

@endsection
