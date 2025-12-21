@extends('backend.layouts.master')
@section('title', 'Stock Lists | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- PAGE TITLE -->
<div class="mb-3">
    <h3>Product Stock List</h3>
    <p class="text-muted mb-0">
    Product stock overview based on applied filters
    </p>
</div>

<!-- ===================================================== -->
<!-- LOW STOCK ALERT (UI ONLY) -->
<!-- ===================================================== -->
<div class="alert alert-warning d-flex align-items-center mb-3">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    <div>
        <strong>Low Stock Alert:</strong>
        Some products are below minimum stock level.
        Please review and reorder soon.
    </div>
</div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fas fa-box"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Products</span>
                <span class="info-box-number">3</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-layer-group"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Units</span>
                <span class="info-box-number">62</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Low Stock</span>
                <span class="info-box-number">1</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-secondary">
                <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Stock Value</span>
                <span class="info-box-number">$58,900</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- STOCK STATUS LEGEND (UI ONLY) -->
<!-- ===================================================== -->
<div class="mb-2">
    <span class="badge badge-success mr-2">Normal Stock</span>
    <span class="badge badge-warning mr-2">Low Stock</span>
    <span class="badge badge-danger">Out of Stock</span>
</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-3">
<div class="card-body">
<div class="row">

    <div class="col-md-3">
        <input type="text" class="form-control"
               placeholder="Search Product / SKU">
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Categories</option>
            <option>Mobile Phone</option>
            <option>Smart TV</option>
            <option>Refrigerator</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Brands</option>
            <option>Apple</option>
            <option>Samsung</option>
            <option>LG</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Stock Status</option>
            <option>Normal</option>
            <option>Low Stock</option>
            <option>Out of Stock</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Shelves</option>
            <option>Mobile Shelf A1</option>
            <option>Mobile Shelf A2</option>
            <option>TV Shelf B1</option>
        </select>
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
<!-- PRODUCT STOCK TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th width="70">Image</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Supplier</th>
    <th width="90">Qty</th>
    <th width="120">Unit Cost</th>

    <!-- 🔹 NEW SELLING COLUMNS -->
    <th width="120">Selling Price</th>
    <th width="120">Profit / Unit</th>

    <th width="130">Stock Value</th>
    <th width="160">Shelf</th>
    <th width="120">Stock Status</th>
    <th width="120" class="text-center">Action</th>
</tr>
</thead>
<tbody>

<!-- NORMAL STOCK -->
<tr>
    <td class="text-center">
        <img src="https://www.myg.in/images/thumbnails/300/300/detailed/75/s24ultraviolet1-removebg-preview.png.png"
             width="45">
    </td>
    <td>
        <strong>Samsung Galaxy S24</strong><br>
        <small class="text-muted">256GB / Factory Unlocked</small>
    </td>
    <td>SGS24</td>
    <td>Global Tech Supply</td>
    <td><strong>30</strong></td>

    <td>$950</td>

    <!-- SELLING -->
    <td><strong>$1,100</strong></td>
    <td class="text-success"><strong>$150</strong></td>

    <td>$28,500</td>
    <td>Mobile Shelf A3</td>
    <td><span class="badge badge-success">Normal</span></td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-primary"
                data-toggle="modal"
                data-target="#productDetailModal">
            View
        </button>
    </td>
</tr>

<!-- LOW STOCK -->
<tr class="table-warning">
    <td class="text-center">
        <img src="https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png"
             width="45">
    </td>
    <td>
        <strong>iPhone 15 Pro</strong><br>
        <small class="text-muted">256GB</small>
    </td>
    <td>IP15P-256</td>
    <td>Global Tech Supply</td>
    <td><strong>22</strong></td>

    <td>$950</td>

    <!-- SELLING -->
    <td><strong>$1,150</strong></td>
    <td class="text-success"><strong>$200</strong></td>

    <td>$20,900</td>
    <td>Mobile Shelf A1</td>
    <td><span class="badge badge-warning">Low Stock</span></td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-primary"
                data-toggle="modal"
                data-target="#productDetailModal">
            View
        </button>
    </td>
</tr>

</tbody>
</table>

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
</div>




<!-- ===================================================== -->
<!-- PRODUCT DETAIL MODAL (READ ONLY) -->
<!-- ===================================================== -->
<div class="modal fade" id="productDetailModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

    <div class="modal-header">
        <h5 class="modal-title">Product Details</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">

        <div class="row mb-3">
            <div class="col-md-3 text-center">
                <img src="https://www.myg.in/images/thumbnails/300/300/detailed/75/s24ultraviolet1-removebg-preview.png.png"
                    class="img-fluid rounded">
            </div>
            <div class="col-md-9">
                <h5>Samsung Galaxy S24</h5>
                <p class="text-muted mb-1">
                    SKU: SGS24 · Brand: Samsung · Category: Mobile Phone
                </p>
                <span class="badge badge-success">Normal Stock</span>
            </div>
        </div>

        <table class="table table-sm table-bordered">
            <tr><th width="35%">Supplier</th><td>Global Tech Supply</td></tr>
            <tr><th>Storage Location</th><td>Mobile Shelf A3</td></tr>
            <tr><th>Current Quantity</th><td><strong>30 Units</strong></td></tr>
            <tr><th>Unit Cost</th><td>$950</td></tr>
            <tr><th>Total Stock Value</th><td><strong>$28,500</strong></td></tr>
            <tr><th>Last Updated</th><td>2025-01-15</td></tr>
        </table>

        <div class="mt-3">
            <h6>Description</h6>
            <div class="border rounded p-2 bg-light"
                style="max-height:180px; overflow:auto;">
                Samsung Galaxy S24 features a 6.1-inch AMOLED display,
                premium performance, factory unlocked support,
                and 5G connectivity.
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>

</div>
</div>

</section>
</div>

@endsection
