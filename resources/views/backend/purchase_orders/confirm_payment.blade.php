@extends('backend.layouts.master')
@section('title', 'Confirm Payment| Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

  {{-- CONFIRMATION MESSAGE --}}
  @if(session('last_po_ids'))
  <div class="alert alert-success text-center">
      <h4 class="mb-1">
          <i class="fas fa-check-circle"></i>
          Purchase Order Confirmed Successfully
      </h4>
      <p class="mb-0 text-white">
          Your order has been confirmed and recorded. This invoice serves as official purchase documentation.
      </p>
  </div>
  @endif

  @forelse($purchase_orders as $po)
    {{-- ===================== SUPPLIER INVOICE ===================== --}}
    <div class="invoice p-3 mb-4 border rounded bg-white">

        {{-- HEADER --}}
        <div class="row">
            <div class="col-12">
                <h4 class="mb-0">
                    <i class="fas fa-globe"></i> Your Wholesale Co.
                    <small class="float-right">Date: {{ $po->order_date->format('d/m/Y') }}</small>
                </h4>
                <hr>
            </div>
        </div>

        {{-- FROM / TO / INFO --}}
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <strong>From</strong>
                <address class="mb-0">
                    <strong>Your Wholesale Co.</strong><br>
                    Phnom Penh<br>
                    Phone: 012 345 678<br>
                    Email: purchase@yourcompany.com
                </address>
            </div>

            <div class="col-sm-4 invoice-col">
                <strong>To</strong>
                <address class="mb-0">
                    <strong>{{ $po->supplier->company_name }}</strong><br>
                    {{ $po->supplier->address ?? 'N/A' }}<br>
                    Phone: {{ $po->supplier->phone ?? 'N/A' }}<br>
                    Email: {{ $po->supplier->email ?? 'N/A' }}
                </address>
            </div>

            <div class="col-sm-4 invoice-col">
                <b>Purchase Order #{{ $po->po_number }}</b><br>
                <b>Status:</b> <span class="badge badge-success">{{ ucfirst($po->status) }}</span><br>
                <b>Payment Status:</b> <span class="badge badge-info">{{ ucfirst($po->payment_status) }}</span><br>
                <b>Payment Due:</b> {{ $po->due_date ? $po->due_date->format('d/m/Y') : '—' }}<br>
                <b>Payment Term:</b> {{ $po->payment_term ?? '—' }}<br>
                <b>Lead Time:</b> {{ $po->supplier->lead_time_days ?? '0' }} Days
            </div>
        </div>

        {{-- ITEMS --}}
        <div class="row mt-3">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th style="width: 80px;">Image</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Unit Cost</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($po->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @php
                                    $imageUrl = asset('assets/dist/img/default-150x150.png');
                                    if ($item->product->image) {
                                        $imageUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($item->product->image);
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" class="img-fluid rounded" style="max-height: 50px;">
                            </td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->product->sku }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->unit_cost, 2) }}</td>
                            <td class="text-right">${{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TOTAL --}}
        <div class="row mt-3">
            <div class="col-6">
                @if($po->remarks)
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        <strong>Note:</strong> {{ $po->remarks }}
                    </p>
                @endif
                @if($po->payment_method)
                    <p class="text-muted well well-sm shadow-none">
                        <strong>Payment Method:</strong> {{ $po->payment_method }}
                    </p>
                @endif
            </div>
            <div class="col-6">
                <table class="table">
                    <tr><th style="width:50%">Subtotal</th><td class="text-right">${{ number_format($po->total_amount, 2) }}</td></tr>
                    <tr><th>Tax (0%)</th><td class="text-right">$0.00</td></tr>
                    <tr class="border-top"><th>Total</th><td class="text-right"><strong>${{ number_format($po->total_amount, 2) }}</strong></td></tr>
                </table>
            </div>
        </div>

    </div>
  @empty
    <div class="alert alert-warning">
        No purchase orders found.
    </div>
  @endforelse

  {{-- ACTIONS --}}
  <div class="row no-print mt-3">
      <div class="col-12">
          @if(auth()->user()->role === 'supplier')
            <a href="{{ route('supplier.orders.manage') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Manage Orders
            </a>
          @else
            <a href="{{ route('product_management.index') }}" class="btn btn-secondary">
                <i class="fas fa-shopping-bag mr-1"></i> Back to Products
            </a>
          @endif

          <button class="btn btn-default ml-2" onclick="window.print()">
              <i class="fas fa-print"></i> Print
          </button>

          <button class="btn btn-primary float-right" onclick="window.print()">
              <i class="fas fa-download"></i> Generate PDF
          </button>
      </div>
  </div>

</section>
</div>

@endsection
