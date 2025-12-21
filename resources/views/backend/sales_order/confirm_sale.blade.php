@extends('backend.layouts.master')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- ===================================================== -->
<!-- CONFIRMATION MESSAGE -->
<!-- ===================================================== -->
<div class="alert alert-success text-center">
    <h4>
        <i class="fas fa-check-circle"></i>
        Sale Confirmed Successfully
    </h4>
    <p class="mb-0">
        The sale has been completed and recorded.
        This invoice serves as official sales documentation.
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
            <i class="fas fa-store"></i> Your Wholesale Co.
            <small class="float-right">
                Date: {{ date('d/m/Y') }}
            </small>
        </h4>
    </div>
</div>

<!-- FROM / TO -->
<div class="row invoice-info mt-4">

    <!-- FROM -->
    <div class="col-sm-4 invoice-col">
        From
        <address>
            <strong>Your Wholesale Co.</strong><br>
            Phnom Penh<br>
            Phone: 012 345 678<br>
            Email: sales@yourcompany.com
        </address>
    </div>

    <!-- TO -->
    <div class="col-sm-4 invoice-col">
        To
        <address>
            <strong>Walk-in Customer</strong><br>
            Customer Type: B2C<br>
            Phone: —<br>
            Email: —
        </address>
    </div>

    <!-- INFO -->
    <div class="col-sm-4 invoice-col">
        <b>Sales Invoice #SO-0001</b><br>
        <b>Status:</b> Paid<br>
        <b>Payment Method:</b> Cash<br>
        <b>Payment Terms:</b> Full Payment
    </div>

</div>

<!-- ===================================================== -->
<!-- PRODUCT TABLE -->
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
    <th class="text-right">Unit Price</th>
    <th class="text-right">Subtotal</th>
</tr>
</thead>

<tbody>
<tr>
    <td>1</td>
    <td>iPhone 15 Pro</td>
    <td>IP15P-256</td>
    <td>256GB, Factory Unlocked</td>
    <td class="text-center">1</td>
    <td class="text-right">$1,800.00</td>
    <td class="text-right">$1,800.00</td>
</tr>

<tr>
    <td>2</td>
    <td>Samsung Galaxy S24</td>
    <td>SGS24</td>
    <td>256GB, Factory Unlocked</td>
    <td class="text-center">1</td>
    <td class="text-right">$1,500.00</td>
    <td class="text-right">$1,500.00</td>
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
                <td>$3,300.00</td>
            </tr>
            <tr>
                <th>Tax</th>
                <td>$0.00</td>
            </tr>
            <tr class="border-top">
                <th>Total</th>
                <td><strong>$3,300.00</strong></td>
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

    <a href="{{ route('sales_order.index') }}"
       class="btn btn-secondary">
        Back to Sales Order
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
