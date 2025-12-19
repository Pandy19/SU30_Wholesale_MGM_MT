@extends('backend.layouts.master')
@section('main-content')

<div class="content-wrapper">

    <!-- ================= CONTENT HEADER ================= -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Supplier Products</h1>
                    <p class="text-muted mb-0">
                        Review products offered by suppliers and choose where to buy.
                    </p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Supplier Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= MAIN CONTENT ================= -->
    <section class="content">
        <div class="container-fluid">

            <!-- ================= FILTER BAR ================= -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-control" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="mobile phone">Mobile Phone</option>
                        <option value="smart tv">Smart TV</option>
                        <option value="washing machine">Washing Machine</option>
                        <option value="air conditioner">Air Conditioner</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-control" id="brandFilter">
                        <option value="">All Brands</option>
                        <option value="apple">Apple</option>
                        <option value="samsung">Samsung</option>
                        <option value="lg">LG</option>
                        <option value="sony">Sony</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <input type="text"
                           class="form-control"
                           id="productSearch"
                           placeholder="Search product name or SKU">
                </div>
            </div>

            <!-- ================= PRODUCT CATALOG ================= -->
            <div class="card">
                <div class="card-body p-0">

                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th width="70">Image</th>
                                <th>Product</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Suppliers</th>
                                <th>Best Price</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- ================= PRODUCT ROW ================= -->
                            <tr data-brand="apple" data-category="mobile phone">
                                <td class="text-center">
                                    <img src="https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png?v=cc5784d1"
                                         class="img-fluid rounded">
                                </td>
                                <td>
                                    <strong>iPhone 15 Pro 256GB</strong><br>
                                    <small class="text-muted">SKU: IP15P-256</small>
                                </td>
                                <td>Apple</td>
                                <td>Mobile Phone</td>
                                <td>
                                    <span class="badge badge-info">2 Suppliers</span>
                                </td>
                                <td>$950</td>
                                <td>
                                    <span class="badge badge-success">Available</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success"
                                            data-toggle="modal"
                                            data-target="#supplierSelectModal">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- ================= PRODUCT ROW ================= -->
                            <tr data-brand="samsung" data-category="mobile phone">
                                <td class="text-center">
                                    <img src="https://www.myg.in/images/thumbnails/300/300/detailed/75/s24ultraviolet1-removebg-preview.png.png"
                                         class="img-fluid rounded">
                                </td>
                                <td>
                                    <strong>Samsung Galaxy S24</strong><br>
                                    <small class="text-muted">SKU: SGS24</small>
                                </td>
                                <td>Samsung</td>
                                <td>Mobile Phone</td>
                                <td>
                                    <span class="badge badge-info">1 Supplier</span>
                                </td>
                                <td>$720</td>
                                <td>
                                    <span class="badge badge-warning">Limited</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success"
                                            data-toggle="modal"
                                            data-target="#supplierSelectModal">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </td>
                            </tr>
                          
                        </tbody>
                    </table>
<!-- ================= PAGINATION (UI ONLY) ================= -->
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
            </div>

        </div>
    </section>
</div>

<!-- ================= SUPPLIER SELECTION MODAL ================= -->
<div class="modal fade" id="supplierSelectModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Choose Supplier</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <!-- PRODUCT INFO -->
                <div class="row mb-3 align-items-center">
                    <div class="col-md-2 text-center">
                        <img src="https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png?v=cc5784d1"
                             class="img-fluid rounded">
                    </div>
                    <div class="col-md-10">
                        <h5 class="mb-1">iPhone 15 Pro 256GB</h5>
                        <small class="text-muted">
                            Brand: Apple | SKU: IP15P-256
                        </small>
                    </div>
                </div>

                <!-- SUPPLIER OFFER TABLE -->
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Supplier</th>
                            <th>Supplier Code</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Cost Price</th>
                            <th>Available Qty</th>
                            <th>Lead Time</th>
                            <th>Status</th>
                            <th width="110">Select</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>Global Tech Supply</td>
                            <td>SUP-001</td>
                            <td>012 345 678</td>
                            <td>info@globaltech.com</td>
                            <td>$950</td>
                            <td><span class="badge badge-success">120</span></td>
                            <td>5 Days</td>
                            <td><span class="badge badge-success">Available</span></td>
                            <td class="text-center">
                                <a href="{{url('/purchase_orders')}}" class="btn btn-sm btn-primary">Choose</a>
                            </td>
                        </tr>

                        <tr>
                            <td>Asia Mobile Distribution</td>
                            <td>SUP-002</td>
                            <td>098 765 432</td>
                            <td>sales@asiamobile.com</td>
                            <td>$980</td>
                            <td><span class="badge badge-warning">40</span></td>
                            <td>3 Days</td>
                            <td><span class="badge badge-success">Available</span></td>
                            <td class="text-center">
                                <a href="{{url('/purchase_orders')}}" class="btn btn-sm btn-primary">Choose</a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
