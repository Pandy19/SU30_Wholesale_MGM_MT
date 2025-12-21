@extends('backend.layouts.master')
@section('title', 'Confirm Payment| Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- ===================================================== -->
<!-- CONFIRMATION MESSAGE -->
<!-- ===================================================== -->
<div class="alert alert-success text-center">
    <h4>
        <i class="fas fa-check-circle"></i>
        Purchase Order Confirmed Successfully
    </h4>
    <p class="mb-0">
        Your order has been confirmed and recorded.
        This invoice serves as official purchase documentation.
    </p>
</div>

<!-- ===================================================== -->
<!-- INVOICE -->
<!-- ===================================================== -->
<div class="invoice p-3 mb-3">

<!-- HEADER -->
<div class="row">
    <div class="col-12">
        <h4>
            <i class="fas fa-globe"></i> Your Wholesale Co.
            <small class="float-right">Date: {{ date('d/m/Y') }}</small>
        </h4>
    </div>
</div>

<!-- FROM / TO -->
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
        <b>Purchase Order #PO-0001</b><br>
        <b>Status:</b> Confirmed<br>
        <b>Payment Terms:</b> Net 7<br>
        <b>Payment Method:</b> Bank Transfer
    </div>
</div>

<!-- ===================================================== -->
<!-- PRODUCT TABLE (READ ONLY) -->
<!-- ===================================================== -->
<div class="row mt-4">
<div class="col-12 table-responsive">
<table class="table table-striped">
<thead>
<tr>
    <th>#</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Description</th>
    <th class="text-center">Qty</th>
    <th class="text-right">Subtotal</th>
</tr>
</thead>
<tbody>

<tr>
    <td>1</td>
    <td>Samsung Galaxy S24</td>
    <td>SGS24</td>
    <td>Mobile Phone – 256GB, Factory Unlocked</td>
    <td class="text-center">1</td>
    <td class="text-right">$950.00</td>
</tr>

<tr>
    <td>2</td>
    <td>Samsung Smart TV 55"</td>
    <td>SS-TV55</td>
    <td>4K UHD Smart TV, HDR, Wi-Fi Enabled</td>
    <td class="text-center">1</td>
    <td class="text-right">$700.00</td>
</tr>

</tbody>
</table>
</div>
</div>

<!-- ===================================================== -->
<!-- TOTAL -->
<!-- ===================================================== -->
<div class="row mt-4">
<div class="col-6"></div>
<div class="col-6">
    <div class="table-responsive">
        <table class="table">
            <tr>
                <th style="width:50%">Subtotal</th>
                <td>$1,650.00</td>
            </tr>
            <tr>
                <th>Tax</th>
                <td>$0.00</td>
            </tr>
            <tr class="border-top">
                <th>Total</th>
                <td><strong>$1,650.00</strong></td>
            </tr>
        </table>
    </div>
</div>
</div>

<!-- ===================================================== -->
<!-- ACTIONS -->
<!-- ===================================================== -->
<div class="row no-print mt-4">
<div class="col-12">
    <a href="{{ route('purchase_orders.index') }}"
       class="btn btn-secondary">
        Back to Purchase Orders
    </a>

    <button class="btn btn-default ml-2" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </button>

    <button class="btn btn-primary float-right" onclick="window.print()">
        <i class="fas fa-download"></i> Generate PDF
    </button>
</div>
</div>

</div>

</section>
</div>

@endsection
