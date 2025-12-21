@extends('backend.layouts.master')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- PAGE TITLE + EXPORT -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3>Stock Ledger (Audit Trail)</h3>
        <p class="text-muted mb-0">
            Complete history of stock movements for audit and verification
        </p>
    </div>

    <!-- EXPORT BUTTONS -->
    <div>
        <button class="btn btn-outline-secondary mr-1"
                onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>

        <button class="btn btn-outline-danger mr-1">
            <i class="fas fa-file-pdf"></i> Export PDF
        </button>

        <button class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
    </div>
</div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fas fa-list"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Records</span>
                <span class="info-box-number">18</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-arrow-down"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Stock In</span>
                <span class="info-box-number">+74</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger">
                <i class="fas fa-arrow-up"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Stock Out</span>
                <span class="info-box-number">−12</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-user-check"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Actions Logged</span>
                <span class="info-box-number">100%</span>
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
               placeholder="Search Product / SKU / Reference">
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Products</option>
            <option>Samsung Galaxy S24</option>
            <option>iPhone 15 Pro</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Actions</option>
            <option>Stock In</option>
            <option>Stock Out</option>
            <option>Rejected</option>
            <option>Adjustment</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Users</option>
            <option>Admin</option>
            <option>Staff</option>
        </select>
    </div>

    <div class="col-md-3">
        <input type="date" class="form-control">
    </div>

</div>
</div>
</div>

<!-- ===================================================== -->
<!-- LEDGER TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>Date</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Action</th>
    <th>Qty In</th>
    <th>Qty Out</th>
    <th>Balance</th>
    <th>Reference</th>
    <th>Performed By</th>
    <th>Note</th>
</tr>
</thead>
<tbody>

<tr>
    <td>2025-01-15</td>
    <td>Samsung Galaxy S24</td>
    <td>SGS24</td>
    <td><span class="badge badge-success">Stock In</span></td>
    <td>+7</td>
    <td>—</td>
    <td>30</td>
    <td>PO-0001</td>
    <td>Admin</td>
    <td>Approved goods received</td>
</tr>

<tr>
    <td>2025-01-14</td>
    <td>Samsung Galaxy S24</td>
    <td>SGS24</td>
    <td><span class="badge badge-danger">Rejected</span></td>
    <td>—</td>
    <td>−3</td>
    <td>23</td>
    <td>GRN-0003</td>
    <td>Staff</td>
    <td>Damaged on arrival</td>
</tr>

<tr>
    <td>2025-01-10</td>
    <td>Samsung Galaxy S24</td>
    <td>SGS24</td>
    <td><span class="badge badge-info">Initial Stock</span></td>
    <td>+26</td>
    <td>—</td>
    <td>26</td>
    <td>PO-0001</td>
    <td>Admin</td>
    <td>Purchase confirmed</td>
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
