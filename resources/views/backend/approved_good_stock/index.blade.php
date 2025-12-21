@extends('backend.layouts.master')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- PAGE TITLE -->
<div class="mb-3">
    <h3>Approved Goods → Stock Approval</h3>
    <p class="text-muted mb-0">
        Add approved goods into stock (single warehouse)
    </p>
</div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-boxes"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Approved Items</span>
                <span class="info-box-number">6</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fas fa-hourglass-half"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Pending Stock Add</span>
                <span class="info-box-number">4</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-secondary">
                <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Added to Stock</span>
                <span class="info-box-number">2</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger">
                <i class="fas fa-exclamation-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">High Value Pending</span>
                <span class="info-box-number">$12,400</span>
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
               placeholder="Search Product / SKU / PO No">
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Brands</option>
            <option>Samsung</option>
            <option>Apple</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Categories</option>
            <option>Mobile Phone</option>
            <option>Smart TV</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Suppliers</option>
            <option>Global Tech Supply</option>
            <option>Asia Mobile Distribution</option>
        </select>
    </div>

    <div class="col-md-3">
        <select class="form-control">
            <option>All Status</option>
            <option>Pending</option>
            <option>Added to Stock</option>
        </select>
    </div>

</div>
</div>
</div>

<!-- ===================================================== -->
<!-- APPROVED GOODS TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>Image</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Brand</th>
    <th>Supplier</th>
    <th>Approved Qty</th>
    <th>Unit Cost</th>
    <th>Total Value</th>
    <th>PO No</th>
    <th>Storage Location</th>
    <th>Approved By</th>
    <th>Approved Date</th>
    <th>Status</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

<!-- ================= PENDING ================= -->
<tr>
    <td class="text-center">
        <img src="https://www.myg.in/images/thumbnails/300/300/detailed/75/s24ultraviolet1-removebg-preview.png.png"
             width="50">
    </td>
    <td>Samsung Galaxy S24</td>
    <td>SGS24</td>
    <td>Samsung</td>
    <td>Global Tech Supply</td>
    <td><strong>7</strong></td>
    <td>$950</td>
    <td>$6,650</td>
    <td>PO-0001</td>

    <!-- STORAGE LOCATION (CATEGORY SHELF ONLY) -->
    <td>
        <select class="form-control form-control-sm">
            <option selected disabled>Select shelf</option>
            <option>Mobile Shelf A1</option>
            <option>Mobile Shelf A2</option>
            <option>Mobile Shelf A3</option>
        </select>
    </td>

    <td>Admin</td>
    <td>2025-01-15</td>
    <td>
        <span class="badge badge-warning">Pending</span>
    </td>
    <td class="text-center">
        <button class="btn btn-sm btn-success"
                onclick="confirmAddToStock('Samsung Galaxy S24', 7)">
            Add to Stock
        </button>
    </td>
</tr>

<!-- ================= ADDED ================= -->
<tr>
    <td class="text-center">
        <img src="https://images-cdn.ubuy.co.in/668e509932f72820f85b4e0f-samsung-55-class-4k-uhdtv-2160p-hdr.jpg"
             width="50">
    </td>
    <td>Samsung Smart TV 55"</td>
    <td>SS-TV55</td>
    <td>Samsung</td>
    <td>Global Tech Supply</td>
    <td>5</td>
    <td>$700</td>
    <td>$3,500</td>
    <td>PO-0002</td>

    <!-- READ ONLY LOCATION -->
    <td>TV Shelf T1</td>

    <td>Admin</td>
    <td>2025-01-14</td>
    <td>
        <span class="badge badge-success">Added</span>
    </td>
    <td class="text-center text-muted">—</td>
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
