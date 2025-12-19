@extends('backend.layouts.master')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

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
<!-- FILTERS (UI ONLY) -->
<!-- ===================================================== -->
<div class="card mb-3">
<div class="card-body">

<div class="row">
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
    <th>Total</th>
    <th>Payment</th>
    <th>GRN Status</th>
    <th>Order Type</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

<tr>
    <td>PO-0001</td>
    <td>Global Tech Supply</td>
    <td>2025-01-10</td>
    <td>$1,650.00</td>
    <td><span class="badge badge-success">Paid</span></td>
    <td><span class="badge badge-warning">Not Received</span></td>
    <td>Regular</td>
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
    <td>2025-01-12</td>
    <td>$980.00</td>
    <td><span class="badge badge-warning">Unpaid</span></td>
    <td><span class="badge badge-success">Received</span></td>
    <td>Urgent</td>
    <td class="text-center">
        <a href="{{ route('purchase_orders.confirm_payment') }}"
           class="btn btn-sm btn-primary">
            View Invoice
        </a>
    </td>
</tr>

</tbody>
</table>

</div>
</div>

</section>
</div>

@endsection
