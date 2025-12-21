@extends('backend.layouts.master')
@section('title', 'Stock Categorys | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- ===================================================== -->
<!-- PAGE TITLE -->
<!-- ===================================================== -->
<div class="mb-4">
    <h3 class="mb-1">Stock by Category</h3>
    <p class="text-muted mb-0">
        Warehouse stock grouped by category and brand
    </p>
</div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info">
                <i class="fas fa-layer-group"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Categories</span>
                <span class="info-box-number">2</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success">
                <i class="fas fa-boxes"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Stock Qty</span>
                <span class="info-box-number">122</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Low Stock Items</span>
                <span class="info-box-number">3</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-secondary">
                <i class="fas fa-warehouse"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Warehouse</span>
                <span class="info-box-number">Main Store</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-4 shadow-sm">
<div class="card-body">
<div class="row">

    <div class="col-md-4 mb-2">
        <input type="text" class="form-control"
               placeholder="Search Product / SKU">
    </div>

    <div class="col-md-3 mb-2">
        <select class="form-control">
            <option>All Brands</option>
            <option>Apple</option>
            <option>Samsung</option>
        </select>
    </div>

    <div class="col-md-3 mb-2">
        <select class="form-control">
            <option>All Categories</option>
            <option>Mobile Phone</option>
            <option>Smart TV</option>
        </select>
    </div>

    <div class="col-md-2 mb-2">
        <select class="form-control">
            <option>All Status</option>
            <option>Normal</option>
            <option>Low Stock</option>
        </select>
    </div>

</div>
</div>
</div>

<!-- ===================================================== -->
<!-- CATEGORY: MOBILE PHONE -->
<!-- ===================================================== -->
<div class="card mb-4 shadow-sm">

<a href="#categoryMobile" data-toggle="collapse"
   class="text-dark text-decoration-none">
<div class="card-header bg-light">
    <strong>📱 Mobile Phone</strong>
    <span class="badge badge-info ml-2">78 Units</span>
    <span class="float-right">
        <i class="fas fa-chevron-down"></i>
    </span>
</div>
</a>

<div class="collapse show" id="categoryMobile">
<div class="card-body p-0">

<!-- ================= APPLE ================= -->
<a href="#brandAppleStock" class="text-dark text-decoration-none" data-toggle="collapse">
<div class="card-body border-bottom bg-white hover-shadow">
<div class="row align-items-center">
<div class="col-md-1 text-center">
<img src="https://logos-world.net/wp-content/uploads/2020/04/Apple-Logo.png"
     class="img-thumbnail" width="45">
</div>
<div class="col-md-6">
<h5 class="mb-1">Apple</h5>
<small class="text-muted">Category: Mobile Phone</small>
</div>
<div class="col-md-3">
<span class="badge badge-success mr-2">Normal</span>
<span class="badge badge-info">32 Units</span>
</div>
<div class="col-md-2 text-right">
<i class="fas fa-chevron-down"></i>
</div>
</div>
</div>
</a>

<div class="collapse" id="brandAppleStock">
<table class="table table-bordered mb-0">
<thead class="thead-light">
<tr>
<th width="70">Image</th>
<th>Product</th>
<th>SKU</th>
<th width="80">Qty</th>
<th width="110">Unit Cost</th>
<th width="130">Total Value</th>
<th width="160">Shelf</th>
</tr>
</thead>
<tbody>
<tr>
<td class="text-center">
<img src="https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png"
     class="img-thumbnail" width="45">
</td>
<td><strong>iPhone 15 Pro 256GB</strong></td>
<td>IP15P-256</td>
<td><strong>20</strong></td>
<td>$950</td>
<td>$19,000</td>
<td>Mobile Shelf A1</td>
</tr>
</tbody>
</table>
</div>

<!-- ================= SAMSUNG ================= -->
<a href="#brandSamsungStock" class="text-dark text-decoration-none" data-toggle="collapse">
<div class="card-body border-top border-bottom bg-white hover-shadow">
<div class="row align-items-center">
<div class="col-md-1 text-center">
<img src="https://logos-world.net/wp-content/uploads/2020/04/Samsung-Logo.png"
     class="img-thumbnail" width="45">
</div>
<div class="col-md-6">
<h5 class="mb-1">Samsung</h5>
<small class="text-muted">Category: Mobile Phone</small>
</div>
<div class="col-md-3">
<span class="badge badge-success mr-2">Normal</span>
<span class="badge badge-info">46 Units</span>
</div>
<div class="col-md-2 text-right">
<i class="fas fa-chevron-down"></i>
</div>
</div>
</div>
</a>

<div class="collapse" id="brandSamsungStock">
<table class="table table-bordered mb-0">
<tbody>
<tr>
<td width="70" class="text-center">
<img src="https://www.myg.in/images/thumbnails/300/300/detailed/75/s24ultraviolet1-removebg-preview.png.png"
     class="img-thumbnail" width="45">
</td>
<td><strong>Samsung Galaxy S24</strong></td>
<td>SGS24</td>
<td width="80"><strong>30</strong></td>
<td width="110">$950</td>
<td width="130">$28,500</td>
<td width="160">Mobile Shelf A3</td>
</tr>
</tbody>
</table>
</div>

</div>
</div>

<!-- ===================================================== -->
<!-- CATEGORY: SMART TV -->
<!-- ===================================================== -->
<div class="card mb-4 shadow-sm">

<a href="#categoryTV" data-toggle="collapse"
   class="text-dark text-decoration-none">
<div class="card-header bg-light">
    <strong>📺 Smart TV</strong>
    <span class="badge badge-info ml-2">44 Units</span>
    <span class="float-right">
        <i class="fas fa-chevron-down"></i>
    </span>
</div>
</a>

<div class="collapse" id="categoryTV">
<div class="card-body p-0">

<a href="#brandSamsungTV" class="text-dark text-decoration-none" data-toggle="collapse">
<div class="card-body border-bottom bg-white hover-shadow">
<div class="row align-items-center">
<div class="col-md-1 text-center">
<img src="https://logos-world.net/wp-content/uploads/2020/04/Samsung-Logo.png"
     class="img-thumbnail" width="45">
</div>
<div class="col-md-6">
<h5 class="mb-1">Samsung</h5>
<small class="text-muted">Category: Smart TV</small>
</div>
<div class="col-md-3">
<span class="badge badge-warning mr-2">Low Stock</span>
<span class="badge badge-info">44 Units</span>
</div>
<div class="col-md-2 text-right">
<i class="fas fa-chevron-down"></i>
</div>
</div>
</div>
</a>

<div class="collapse" id="brandSamsungTV">
<table class="table table-bordered mb-0">
<tbody>
<tr>
<td width="70" class="text-center">
<img src="https://images-cdn.ubuy.co.in/668e509932f72820f85b4e0f-samsung-55-class-4k-uhdtv-2160p-hdr.jpg"
     class="img-thumbnail" width="45">
</td>
<td><strong>Samsung Smart TV 55"</strong></td>
<td>SS-TV55</td>
<td><strong>22</strong></td>
<td>$700</td>
<td>$15,400</td>
<td>TV Shelf T1</td>
</tr>
</tbody>
</table>
</div>

</div>
</div>

</section>
</div>

@endsection
