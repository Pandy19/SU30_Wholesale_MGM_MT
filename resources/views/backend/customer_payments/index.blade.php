@extends('backend.layouts.master')
@section('title', 'Customer Payment | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- ===================================================== -->
<!-- PAGE TITLE -->
<!-- ===================================================== -->
<div class="mb-3">
    <h3>Customer Payments</h3>
    <p class="text-muted mb-0">
        Track and receive payments from B2B & B2C customers
    </p>
</div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fas fa-file-invoice-dollar"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Invoices</span>
                <span class="info-box-number">18</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Paid Invoices</span>
                <span class="info-box-number">11</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-clock"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Pending Payments</span>
                <span class="info-box-number">5</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Overdue Amount</span>
                <span class="info-box-number">$9,200</span>
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
               placeholder="Search Invoice / Customer">
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
            <option>Partial</option>
            <option>Unpaid</option>
            <option>Overdue</option>
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
<!-- PAYMENT TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>Invoice No</th>
    <th>Customer</th>
    <th>Type</th>
    <th>Invoice Date</th>
    <th>Due Date</th>
    <th>Total</th>
    <th>Paid</th>
    <th>Balance</th>
    <th>Status</th>
    <th width="160" class="text-center">Action</th>
</tr>
</thead>

<tbody>

<!-- PAID -->
<tr>
    <td>SI-0001</td>
    <td>Walk-in Customer</td>
    <td><span class="badge badge-info">B2C</span></td>
    <td>2025-01-18</td>
    <td>—</td>
    <td>$3,300</td>
    <td>$3,300</td>
    <td>$0</td>
    <td><span class="badge badge-success">Paid</span></td>
    <td class="text-center text-muted">
        —
    </td>
</tr>

<!-- PARTIAL -->
<tr>
    <td>SI-0002</td>
    <td>ABC Mobile Shop</td>
    <td><span class="badge badge-warning">B2B</span></td>
    <td>2025-01-19</td>
    <td>2025-01-26</td>
    <td>$12,400</td>
    <td>$5,000</td>
    <td>$7,400</td>
    <td><span class="badge badge-warning">Partial</span></td>
    <td class="text-center">
        <button class="btn btn-sm btn-success"
                data-toggle="modal"
                data-target="#receivePaymentModal">
            Receive Payment
        </button>
    </td>
</tr>

<!-- OVERDUE -->
<tr class="table-danger">
    <td>SI-0003</td>
    <td>Tech Partner Co.</td>
    <td><span class="badge badge-warning">B2B</span></td>
    <td>2025-01-10</td>
    <td>2025-01-17</td>
    <td>$8,700</td>
    <td>$0</td>
    <td>$8,700</td>
    <td><span class="badge badge-danger">Overdue</span></td>
    <td class="text-center">
        <button class="btn btn-sm btn-success"
                data-toggle="modal"
                data-target="#receivePaymentModal">
            Receive Payment
        </button>
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

<!-- ===================================================== -->
<!-- RECEIVE PAYMENT MODAL (UI ONLY) -->
<!-- ===================================================== -->
<div class="modal fade" id="receivePaymentModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header">
    <h5 class="modal-title">Receive Payment</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

    <div class="form-group">
        <label><strong>Payment Amount</strong></label>
        <input type="number" class="form-control" placeholder="Enter amount">
    </div>

    <div class="form-group">
        <label><strong>Payment Method</strong></label>
        <select class="form-control">
            <option>Cash</option>
            <option>Bank Transfer</option>
            <option>Digital Wallet</option>
            <option>Cheque</option>
        </select>
    </div>

    <div class="form-group">
        <label><strong>Payment Date</strong></label>
        <input type="date" class="form-control">
    </div>

    <div class="form-group">
        <label><strong>Reference / Note</strong></label>
        <textarea class="form-control" rows="2"
                  placeholder="Transaction ID / Cheque No"></textarea>
    </div>

</div>

<div class="modal-footer">
    <button class="btn btn-secondary" data-dismiss="modal">
        Cancel
    </button>
    <button class="btn btn-success"
            onclick="alert('Payment recorded successfully (UI only)')">
        Confirm Payment
    </button>
</div>

</div>
</div>
</div>

@endsection
