@extends('backend.layouts.master')
@section('title', 'Confirm Payment | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- ===================================================== -->
<!-- CONFIRMATION MESSAGE -->
<!-- ===================================================== -->
<div class="alert alert-success text-center border-0 shadow-sm">
    <h4 class="mb-1">
        <i class="fas fa-check-circle"></i>
        Sale Confirmed Successfully
    </h4>
    <p class="mb-0">
        Order <strong>#{{ $order->order_number }}</strong> has been completed and recorded.
        This invoice serves as official sales documentation.
    </p>
</div>

<!-- ===================================================== -->
<!-- INVOICE -->
<!-- ===================================================== -->
<div class="invoice p-3 mb-3 shadow-sm border rounded bg-white">

<!-- HEADER -->
<div class="row">
    <div class="col-12 border-bottom pb-2">
        <h4 class="font-weight-bold">
            <i class="fas fa-store"></i> WHOLESALE MGM
            <small class="float-right text-muted">
                Date: {{ $order->order_date ? date('d/m/Y', strtotime($order->order_date)) : date('d/m/Y') }}
            </small>
        </h4>
    </div>
</div>

<!-- FROM / TO -->
<div class="row invoice-info mt-4">

    <!-- FROM -->
    <div class="col-sm-4 invoice-col">
        <span class="text-muted small text-uppercase font-weight-bold">From</span>
        <address>
            <strong class="text-primary">WHOLESALE MGM</strong><br>
            123 Business Avenue<br>
            Phnom Penh, Cambodia<br>
            Phone: (855) 12 345 678<br>
            Email: sales@wholesalemgm.com
        </address>
    </div>

    <!-- TO -->
    <div class="col-sm-4 invoice-col">
        <span class="text-muted small text-uppercase font-weight-bold">To</span>
        <address>
            <strong class="text-primary">{{ $order->customer->name }}</strong><br>
            Customer Type: {{ $order->customer->type }}<br>
            Phone: {{ $order->customer->phone ?: '—' }}<br>
            Email: {{ $order->customer->email ?: '—' }}
        </address>
    </div>

    <!-- INFO -->
    <div class="col-sm-4 invoice-col border-left pl-3">
        <b>Sales Invoice #{{ $order->order_number }}</b><br>
        <b>Status:</b> <span class="badge badge-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span><br>
        <b>Payment Method:</b> {{ ucfirst($order->payment_method) }}<br>
        <b>Payment Terms:</b> {{ $order->customer->type == 'B2B' ? 'Wholesale Credit' : 'Full Payment' }}
    </div>

</div>

<!-- ===================================================== -->
<!-- PRODUCT TABLE -->
<!-- ===================================================== -->
<div class="row mt-4">
<div class="col-12 table-responsive">

<table class="table table-striped table-sm">
<thead class="bg-light">
<tr>
    <th>#</th>
    <th>Product</th>
    <th>SKU</th>
    <th class="text-center">Qty</th>
    <th class="text-right">Unit Price</th>
    <th class="text-right pr-3">Subtotal</th>
</tr>
</thead>

<tbody>
@foreach($order->items as $index => $item)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $item->product->name }}</td>
    <td><span class="badge badge-light border">{{ $item->product->sku }}</span></td>
    <td class="text-center">{{ $item->quantity }}</td>
    <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
    <td class="text-right font-weight-bold pr-3">${{ number_format($item->line_total, 2) }}</td>
</tr>
@endforeach
</tbody>
</table>

</div>
</div>

<!-- ===================================================== -->
<!-- TOTAL -->
<!-- ===================================================== -->
<div class="row mt-4">
<div class="col-6">
    <p class="text-muted small">
        <strong>Notes:</strong><br>
        {{ $order->payment_note ?: 'Thank you for choosing Wholesale MGM!' }}
    </p>
</div>

<div class="col-6">
    <div class="table-responsive">
        <table class="table table-sm table-borderless">
            <tr>
                <th style="width:50%" class="text-right">Subtotal:</th>
                <td class="text-right pr-3">${{ number_format($order->total_amount, 2) }}</td>
            </tr>
            <tr>
                <th class="text-right">Tax (0%):</th>
                <td class="text-right pr-3">$0.00</td>
            </tr>
            <tr class="border-top">
                <th class="text-right h5 font-weight-bold">Total:</th>
                <td class="text-right text-success h5 font-weight-bold pr-3">${{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>
</div>
</div>

<!-- ===================================================== -->
<!-- ACTIONS -->
<!-- ===================================================== -->
<div class="row no-print mt-4 border-top pt-3">
<div class="col-12">

    <a href="{{ route('sales_order.index') }}"
       class="btn btn-secondary shadow-sm">
       <i class="fas fa-arrow-left mr-1"></i> Back to Sales Order
    </a>

    <button class="btn btn-default ml-2 shadow-sm border" onclick="window.print()">
        <i class="fas fa-print mr-1"></i> Print
    </button>

    <button class="btn btn-primary float-right shadow-sm" onclick="window.print()">
        <i class="fas fa-download mr-1"></i> Generate PDF
    </button>

</div>
</div>

</div>

</section>
</div>

@push('styles')
<style>
    @media print {
        .content-wrapper { margin-left: 0 !important; }
        .main-footer, .main-sidebar, .main-header, .no-print, .alert { display: none !important; }
        .invoice { border: none !important; padding: 0 !important; box-shadow: none !important; }
    }
</style>
@endpush

@endsection
