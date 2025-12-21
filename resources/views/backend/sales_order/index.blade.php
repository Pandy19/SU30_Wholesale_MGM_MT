@extends('backend.layouts.master')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- ===================================================== -->
<!-- PAGE TITLE -->
<!-- ===================================================== -->
<div class="mb-3">
    <h3>Sales Order</h3>
    <p class="text-muted mb-0">
        Sell products to B2B / B2C customers with automatic pricing
    </p>
</div>

<!-- ===================================================== -->
<!-- CUSTOMER SELECTION -->
<!-- ===================================================== -->
<div class="card mb-4">
<div class="card-header">
    <strong>Customer Information</strong>
</div>
<div class="card-body">

<div class="row">

    <div class="col-md-4">
        <label><strong>Select Customer</strong></label>
        <select class="form-control">
            <option>Select Customer</option>
            <option>Walk-in Customer (B2C)</option>
            <option>ABC Mobile Shop (B2B)</option>
            <option>Tech Partner Co. (B2B)</option>
        </select>
    </div>

    <div class="col-md-4">
        <label><strong>Customer Type</strong></label>
        <input type="text" class="form-control" value="B2C / B2B (Auto)" disabled>
    </div>

    <div class="col-md-4">
        <label><strong>Payment Rule</strong></label>
        <input type="text" class="form-control" value="B2C: Full Payment | B2B: Credit Allowed" disabled>
    </div>

</div>

</div>
</div>

<!-- ===================================================== -->
<!-- CATEGORY & BRAND FILTER (NEW - UI ONLY) -->
<!-- ===================================================== -->
<div class="card mb-3">
<div class="card-body">

<div class="row">

    <div class="col-md-6">
        <label><strong>Category</strong></label>
        <select class="form-control">
            <option>All Categories</option>
            <option>Mobile Phone</option>
            <option>Smart TV</option>
            <option>Laptop</option>
        </select>
    </div>

    <div class="col-md-6">
        <label><strong>Brand</strong></label>
        <select class="form-control">
            <option>All Brands</option>
            <option>Apple</option>
            <option>Samsung</option>
            <option>LG</option>
        </select>
    </div>

</div>

</div>
</div>

<!-- ===================================================== -->
<!-- PRODUCT SELECTION -->
<!-- ===================================================== -->
<div class="card mb-4">
<div class="card-header">
    <strong>Add Products</strong>
</div>

<div class="card-body p-0">
<table class="table table-bordered mb-0">
<thead class="thead-light">
<tr>
    <th width="60">Image</th>
    <th>Product</th>
    <th>SKU</th>
    <th width="90">Stock</th>
    <th width="120">B2C Price</th>
    <th width="120">B2B Price</th>
    <th width="100">Qty</th>
    <th width="120">Subtotal</th>
    <th width="100" class="text-center">Action</th>
</tr>
</thead>
<tbody>

<tr>
    <td class="text-center">
        <img src="https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png"
             width="40">
    </td>
    <td>
        <strong>iPhone 15 Pro</strong><br>
        <small class="text-muted">256GB</small>
    </td>
    <td>IP15P-256</td>
    <td><strong>22</strong></td>
    <td>$1,800</td>
    <td>$1,700</td>
    <td>
        <input type="number" class="form-control" value="1" min="1">
    </td>
    <td>$1,800</td>
    <td class="text-center">
        <button class="btn btn-sm btn-success">Add</button>
    </td>
</tr>

<tr>
    <td class="text-center">
        <img src="https://www.myg.in/images/thumbnails/300/300/detailed/75/s24ultraviolet1-removebg-preview.png.png"
             width="40">
    </td>
    <td>
        <strong>Samsung Galaxy S24</strong><br>
        <small class="text-muted">256GB</small>
    </td>
    <td>SGS24</td>
    <td><strong>30</strong></td>
    <td>$1,500</td>
    <td>$1,420</td>
    <td>
        <input type="number" class="form-control" value="1" min="1">
    </td>
    <td>$1,500</td>
    <td class="text-center">
        <button class="btn btn-sm btn-success">Add</button>
    </td>
</tr>

</tbody>
</table>
</div>
</div>

<!-- ===================================================== -->
<!-- CART SUMMARY -->
<!-- ===================================================== -->
<div class="card mb-4">
<div class="card-header">
    <strong>Order Summary</strong>
</div>

<div class="card-body p-0">
<table class="table table-bordered mb-0">
<thead class="thead-light">
<tr>
    <th>Product</th>
    <th width="80">Qty</th>
    <th width="120">Price</th>
    <th width="120">Total</th>
    <th width="80" class="text-center">Remove</th>
</tr>
</thead>
<tbody>

<tr>
    <td>iPhone 15 Pro</td>
    <td>1</td>
    <td>$1,800</td>
    <td>$1,800</td>
    <td class="text-center">
        <button class="btn btn-sm btn-danger">×</button>
    </td>
</tr>

<tr>
    <td>Samsung Galaxy S24</td>
    <td>1</td>
    <td>$1,500</td>
    <td>$1,500</td>
    <td class="text-center">
        <button class="btn btn-sm btn-danger">×</button>
    </td>
</tr>

</tbody>
</table>
</div>
</div>

<!-- ===================================================== -->
<!-- PAYMENT & TOTAL -->
<!-- ===================================================== -->
<div class="row">

<div class="col-md-6">
    <div class="card">
    <div class="card-header">
        <strong>Payment</strong>
    </div>
    <div class="card-body">

        <div class="form-group">
            <label>Payment Method</label>
            <select class="form-control">
                <option>Cash</option>
                <option>Bank Transfer</option>
                <option>Digital Wallet</option>
            </select>
        </div>

        <div class="form-group">
            <label>Payment Terms</label>
            <select class="form-control">
                <option>B2C: Full Payment</option>
                <option>B2B: Net 7 Days</option>
                <option>B2B: Net 15 Days</option>
            </select>
        </div>

        <div class="form-group">
            <label>Note</label>
            <textarea class="form-control" rows="2"
                      placeholder="Optional note"></textarea>
        </div>

    </div>
    </div>
</div>

<div class="col-md-6">
    <div class="card">
    <div class="card-header">
        <strong>Amount Due</strong>
    </div>
    <div class="card-body p-0">

        <table class="table mb-0">
            <tr>
                <th style="width:50%">Subtotal</th>
                <td>$3,300</td>
            </tr>
            <tr>
                <th>Tax</th>
                <td>$0.00</td>
            </tr>
            <tr class="border-top">
                <th>Total</th>
                <td><strong>$3,300</strong></td>
            </tr>
        </table>

    </div>
    </div>
</div>

</div>

<!-- ===================================================== -->
<!-- ACTIONS -->
<!-- ===================================================== -->
<div class="mt-4 text-right">
    <button class="btn btn-secondary">Cancel</button>
    <button class="btn btn-primary ml-2" onclick="confirmSale()">Confirm Sale</button>
</div>

</section>
</div>

<!-- ================= CONFIRM SALE SUCCESS MODAL ================= -->
<div class="modal fade" id="saleSuccessModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center">

            <div class="modal-body p-4">
                <i class="fas fa-check-circle text-success" style="font-size:60px;"></i>
                <h5 class="mt-3">Payment Confirmed</h5>
                <p class="text-muted mb-0">
                    Sale completed successfully
                </p>
            </div>

        </div>
    </div>
</div>


@endsection
