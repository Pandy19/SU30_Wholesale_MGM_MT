@extends('backend.layouts.master')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- ===================================================== -->
<!-- SUPPLIER OFFERS (SWITCH SUPPLIER) -->
<!-- ===================================================== -->
<div class="card mb-4">
    <div class="card-header">
        <strong>Supplier Offers – Samsung Galaxy S24</strong>
        <small class="text-muted ml-2">(Select supplier before confirming)</small>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead class="thead-light">
            <tr>
                <th>Supplier</th>
                <th>Unit Cost</th>
                <th>Available Qty</th>
                <th>Delivery</th>
                <th>Status</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr class="table-success">
                <td><strong>Global Tech Supply</strong></td>
                <td>$950</td>
                <td>40</td>
                <td>5 Days</td>
                <td><span class="badge badge-success">Selected</span></td>
                <td class="text-center text-muted">Current</td>
            </tr>

            <tr>
                <td>Asia Mobile Distribution</td>
                <td>$980</td>
                <td>40</td>
                <td>3 Days</td>
                <td><span class="badge badge-success">Available</span></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary">Switch Supplier</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ===================================================== -->
<!-- PURCHASE ORDER (INVOICE STYLE) -->
<!-- ===================================================== -->
<div class="invoice p-3 mb-3">

<div class="row">
    <div class="col-12">
        <h4>
            <i class="fas fa-globe"></i> Your Wholesale Co.
            <small class="float-right">Date: {{ date('d/m/Y') }}</small>
        </h4>
    </div>
</div>

<div class="row invoice-info mt-4">
    <div class="col-sm-4 invoice-col">
        From
        <address>
            <strong>Your Wholesale Co.</strong><br>
            Phnom Penh<br>
            Phone: 012 345 678<br>
            Email: purchase@yourcompany.com
        </address>
    </div>

    <div class="col-sm-4 invoice-col">
        To
        <address>
            <strong>Global Tech Supply</strong><br>
            Phnom Penh<br>
            Phone: 012 888 999<br>
            Email: info@globaltech.com
        </address>
    </div>

    <div class="col-sm-4 invoice-col">
        <b>Purchase Order #PO-0001</b><br><br>
        <b>Account:</b> SUP-001<br>
        <b>Payment Due:</b> {{ date('d/m/Y', strtotime('+7 days')) }}
    </div>
</div>

<div class="row mt-4">
<div class="col-12 table-responsive">
<table class="table table-striped">
<thead>
<tr>
    <th>#</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Description</th>
    <th style="width:140px">Qty</th>
    <th class="text-right">Subtotal</th>
</tr>
</thead>
<tbody>

<tr>
    <td>1</td>
    <td>Samsung Galaxy S24</td>
    <td>SGS24</td>
    <td>Mobile Phone – 256GB, Factory Unlocked</td>
    <td>
        <input type="number" class="form-control order-qty"
               value="1" min="1" max="40"
               data-price="950" data-available="40">
        <small class="text-muted">Available: 40</small>
    </td>
    <td class="text-right subtotal">$950.00</td>
</tr>

<tr>
    <td>2</td>
    <td>Samsung Smart TV 55"</td>
    <td>SS-TV55</td>
    <td>4K UHD Smart TV, HDR, Wi-Fi Enabled</td>
    <td>
        <input type="number" class="form-control order-qty"
               value="1" min="1" max="20"
               data-price="700" data-available="20">
        <small class="text-muted">Available: 20</small>
    </td>
    <td class="text-right subtotal">$700.00</td>
</tr>

</tbody>
</table>
</div>
</div>

<div class="row mt-4">
<div class="col-6">
    <p class="lead">Payment Information</p>
    <div class="mb-3">
        <img src="{{ asset('assets/dist/img/credit/visa.png') }}">
        <img src="{{ asset('assets/dist/img/credit/mastercard.png') }}">
        <img src="{{ asset('assets/dist/img/credit/paypal2.png') }}">
        <img src="{{ asset('assets/dist/img/credit/american-express.png') }}">
    </div>

    <div class="form-group">
        <label><strong>Payment Method</strong></label>
        <select class="form-control">
            <option>Cash</option>
            <option>Bank Transfer</option>
            <option>Digital Wallet</option>
        </select>
    </div>

    <div class="form-group">
        <label><strong>Payment Terms</strong></label>
        <select class="form-control">
            <option>Immediate</option>
            <option>Net 7 Days</option>
            <option>Net 15 Days</option>
            <option>Net 30 Days</option>
        </select>
    </div>

    <div class="form-group">
        <label><strong>Payment Status</strong></label>
        <select class="form-control">
            <option>Unpaid</option>
            <option>Partial</option>
            <option>Paid</option>
        </select>
    </div>

    <div class="form-group">
        <label><strong>Payment Reference / Note</strong></label>
        <textarea class="form-control" rows="2"
                  placeholder="Transaction ID, cheque no, or internal note"></textarea>
    </div>
</div>

<div class="col-6">
<p class="lead">Amount Due</p>
<table class="table">
<tr><th>Subtotal</th><td>$1,650.00</td></tr>
<tr><th>Tax</th><td>$0.00</td></tr>
<tr><th>Total</th><td><strong>$1,650.00</strong></td></tr>
</table>
</div>
</div>

<div class="row no-print mt-4">
<div class="col-12">
    <button class="btn btn-default" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </button>

    <button class="btn btn-primary float-right ml-2" onclick="window.print()">
        <i class="fas fa-download"></i> Generate PDF
    </button>

    <button id="confirmOrderBtn"
            class="btn btn-success float-right">
        <i class="far fa-check-circle"></i> Confirm Purchase Order
    </button>
</div>
</div>

</div>

</section>
</div>

<!-- SUCCESS MODAL -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="fas fa-check-circle text-success" style="font-size:64px;"></i>
                <h4 class="mt-3">Purchase Order Confirmed</h4>
                <p class="text-muted">Everything looks good.</p>
                <button id="continueBtn" class="btn btn-success mt-3">
                    Continue
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
