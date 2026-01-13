@extends('backend.layouts.master')
@section('title', 'Goods Return | Wholesale MGM')
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
                    <h1 class="m-0">Supplier Returns / Disputes</h1>
                    <p class="text-muted mb-0">
                    Track rejected products and supplier resolution
                    </p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Supliers</a></li>
                        <li class="breadcrumb-item active">Good Return</li>
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
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Disputes</span>
                <span class="info-box-number">6</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-clock"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Pending</span>
                <span class="info-box-number">3</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-sync-alt"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Resolved</span>
                <span class="info-box-number">3</span>
            </div>
        </div>
    </div>

    <!-- ✅ NEW CARD -->
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger">
                <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Refunded / Loss</span>
                <span class="info-box-number">$4,810</span>
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

    <!-- Search -->
    <div class="col-md-3">
        <input type="text" class="form-control"
               placeholder="Search Dispute / PO / Product / SKU">
    </div>

    <!-- Brand -->
    <div class="col-md-2">
        <select class="form-control">
            <option>All Brands</option>
            <option>Samsung</option>
            <option>Apple</option>
        </select>
    </div>

    <!-- Category -->
    <div class="col-md-2">
        <select class="form-control">
            <option>All Categories</option>
            <option>Mobile Phone</option>
            <option>Smart TV</option>
        </select>
    </div>

    <!-- Supplier -->
    <div class="col-md-2">
        <select class="form-control">
            <option>All Suppliers</option>
            <option>Global Tech Supply</option>
            <option>Asia Mobile Distribution</option>
        </select>
    </div>

    <!-- Status -->
    <div class="col-md-3">
        <select class="form-control">
            <option>All Status</option>
            <option>Pending</option>
            <option>Returned</option>
            <option>Replaced</option>
            <option>Refunded</option>
            <option>Closed</option>
        </select>
    </div>

</div>
</div>
</div>

<!-- ===================================================== -->
<!-- DISPUTE TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>Dispute ID</th>
    <th>PO No</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Brand</th>
    <th>Category</th>
    <th>Supplier</th>
    <th>Rejected Qty</th>
    <th>Total Value</th>
    <th>Status</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

<!-- ================= PENDING ================= -->
<tr>
    <td>DSP-0001</td>
    <td>PO-0005</td>
    <td>Samsung Galaxy S24</td>
    <td>SGS24</td>
    <td>Samsung</td>
    <td>Mobile Phone</td>
    <td>Global Tech Supply</td>
    <td>3</td>
    <td>$2,850</td>
    <td><span class="badge badge-warning">Pending</span></td>
    <td class="text-center">
        <button class="btn btn-sm btn-primary"
                data-toggle="modal"
                data-target="#viewDisputeModal">
            <i class="fas fa-eye"></i>
        </button>

        <button class="btn btn-sm btn-warning ml-1"
                data-toggle="modal"
                data-target="#resolveDisputeModal">
            <i class="fas fa-tasks"></i>
        </button>
    </td>
</tr>

<!-- ================= CLOSED ================= -->
<tr>
    <td>DSP-0002</td>
    <td>PO-0003</td>
    <td>iPhone 15 Pro</td>
    <td>IP15P</td>
    <td>Apple</td>
    <td>Mobile Phone</td>
    <td>Asia Mobile Distribution</td>
    <td>2</td>
    <td>$1,960</td>
    <td><span class="badge badge-success">Closed</span></td>
    <td class="text-center">
        <button class="btn btn-sm btn-primary"
                data-toggle="modal"
                data-target="#viewDisputeModal">
            <i class="fas fa-eye"></i>
        </button>

        <button class="btn btn-sm btn-outline-secondary ml-1"
                onclick="window.print()">
            <i class="fas fa-print"></i>
        </button>
    </td>
</tr>

</tbody>
</table>

</div>

<!-- ===================================================== -->
<!-- PAGINATION (UI ONLY) -->
<!-- ===================================================== -->
<div class="card-footer clearfix">
<ul class="pagination pagination-sm m-0 float-right">
    <li class="page-item disabled">
        <a class="page-link">«</a>
    </li>
    <li class="page-item active">
        <a class="page-link">1</a>
    </li>
    <li class="page-item">
        <a class="page-link">2</a>
    </li>
    <li class="page-item">
        <a class="page-link">3</a>
    </li>
    <li class="page-item">
        <a class="page-link">»</a>
    </li>
</ul>
</div>

</div>

</section>
</div>

<!-- ===================================================== -->
<!-- VIEW DISPUTE MODAL -->
<!-- ===================================================== -->
<div class="modal fade" id="viewDisputeModal">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title">Dispute Details</h5>
    <button class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <p><strong>Product:</strong> Samsung Galaxy S24</p>
    <p><strong>Rejected Qty:</strong> 3</p>
    <p><strong>Reason:</strong> Damaged on arrival</p>
    <p><strong>Status:</strong> Pending</p>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<!-- ===================================================== -->
<!-- RESOLVE DISPUTE MODAL -->
<!-- ===================================================== -->
<div class="modal fade" id="resolveDisputeModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title">Resolve Dispute</h5>
    <button class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="form-group">
        <label>Supplier Resolution</label>
        <select class="form-control">
            <option>Returned</option>
            <option>Replaced</option>
            <option>Refunded</option>
        </select>
    </div>
    <div class="form-group">
        <label>Note</label>
        <textarea class="form-control" rows="3"></textarea>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-success" data-dismiss="modal">
        Confirm
    </button>
</div>
</div>
</div>
</div>

@endsection
