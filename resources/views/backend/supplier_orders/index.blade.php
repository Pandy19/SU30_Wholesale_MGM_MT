@extends('backend.layouts.master')
@section('title', 'Supplier Order Historys | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">


  <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Suppliers – Invoice Product Preview</h1>
                    <p class="text-muted mb-0">
                        Click a brand row to view suppliers for each electronic brand.
                    </p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Supliers</a></li>
                        <li class="breadcrumb-item active">Supplier Order History</li>
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
            <span class="info-box-icon bg-info">
                <i class="fas fa-file-invoice"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Orders</span>
                <span class="info-box-number">12</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Amount</span>
                <span class="info-box-number">$24,850</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Paid Orders</span>
                <span class="info-box-number">8</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-clock"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Unpaid Orders</span>
                <span class="info-box-number">4</span>
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
               placeholder="Search PO No (e.g. PO-0001)">
    </div>

    <div class="col-md-3">
        <select class="form-control">
            <option>All Suppliers</option>
            <option>Global Tech Supply</option>
            <option>Asia Mobile Distribution</option>
        </select>
    </div>

    <div class="col-md-3">
        <select class="form-control">
            <option>All Payment Status</option>
            <option>Paid</option>
            <option>Unpaid</option>
        </select>
    </div>

    <div class="col-md-3">
        <input type="date" class="form-control">
    </div>

</div>
</div>
</div>

<!-- ===================================================== -->
<!-- INVOICE TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>PO No</th>
    <th>Supplier</th>
    <th>Order Date</th>
    <th>Invoice Date</th>
    <th>Total</th>
    <th>Payment</th>
    <th>Created By</th>

    <!-- NEW -->
    <th>Receiving Status</th>
    <th>Stock Status</th>

    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

<tr>
    <td>PO-0001</td>
    <td>Global Tech Supply</td>
    <td>2025-01-09</td>
    <td>2025-01-10</td>
    <td>$1,650.00</td>
    <td><span class="badge badge-success">Paid</span></td>
    <td>Admin</td>

    <!-- NEW -->
    <td><span class="badge badge-info">Pending</span></td>
    <td><span class="badge badge-warning">Not Added</span></td>

    <td class="text-center">
        <a href="{{ route('purchase_orders.confirm_payment') }}"
           class="btn btn-sm btn-primary">
            View Invoice
        </a>
        <button class="btn btn-sm btn-outline-secondary ml-1"
                onclick="window.print()">
            Print
        </button>
    </td>
</tr>

<tr>
    <td>PO-0002</td>
    <td>Asia Mobile Distribution</td>
    <td>2025-01-11</td>
    <td>2025-01-12</td>
    <td>$980.00</td>
    <td><span class="badge badge-warning">Unpaid</span></td>
    <td>Staff</td>

    <!-- NEW -->
    <td><span class="badge badge-success">Accepted</span></td>
    <td><span class="badge badge-success">Added to Stock</span></td>

    <td class="text-center">
        <a href="{{ route('purchase_orders.confirm_payment') }}"
           class="btn btn-sm btn-primary">
            View Invoice
        </a>
    </td>
</tr>

<tr>
    <td>PO-0003</td>
    <td>Global Tech Supply</td>
    <td>2025-01-08</td>
    <td>2025-01-08</td>
    <td>$1,200.00</td>
    <td><span class="badge badge-secondary">N/A</span></td>
    <td>Admin</td>

    <!-- NEW -->
    <td><span class="badge badge-danger">Rejected</span></td>
    <td><span class="badge badge-secondary">Not Added</span></td>

    <td class="text-center text-muted">
        —
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
    <li class="page-item disabled">
        <a class="page-link" href="#">«</a>
    </li>
    <li class="page-item active">
        <a class="page-link" href="#">1</a>
    </li>
    <li class="page-item">
        <a class="page-link" href="#">2</a>
    </li>
    <li class="page-item">
        <a class="page-link" href="#">3</a>
    </li>
    <li class="page-item">
        <a class="page-link" href="#">»</a>
    </li>
</ul>
</div>

</div>

</section>
</div>

@endsection
