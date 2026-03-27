@extends(request()->has('no_layout') ? 'backend.layouts.blank' : 'backend.layouts.master')
@section('title', 'Goods Receiving Note | Wholesale MGM')
@section('main-content')

<div class="{{ request()->has('no_layout') ? '' : 'content-wrapper' }}">
<section class="content {{ request()->has('no_layout') ? 'p-0' : 'p-4' }}" style="{{ request()->has('no_layout') ? 'overflow-x: hidden;' : '' }}">

    {{-- ===================== GOODS RECEIVING NOTE ===================== --}}
    <div class="invoice p-3 mb-4 border rounded bg-white">

        {{-- HEADER --}}
        <div class="row">
            <div class="col-12">
                <h4 class="mb-0">
                    <i class="fas fa-receipt"></i> Goods Receiving Note (GRN)
                    <small class="float-right">Date: {{ $gr->received_date ? \Carbon\Carbon::parse($gr->received_date)->format('d/m/Y') : 'N/A' }}</small>
                </h4>
                <hr>
            </div>
        </div>

        {{-- FROM / TO / INFO --}}
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <strong>Received By</strong>
                <address class="mb-0">
                    <strong>Your Wholesale Co.</strong><br>
                    Phnom Penh<br>
                    Phone: 012 345 678<br>
                    Inspector: {{ $gr->approver->name ?? 'N/A' }}
                </address>
            </div>

            <div class="col-sm-4 invoice-col">
                <strong>Supplier</strong>
                <address class="mb-0">
                    <strong>{{ $gr->purchaseOrder->supplier->company_name ?? 'N/A' }}</strong><br>
                    {{ $gr->purchaseOrder->supplier->address ?? 'N/A' }}<br>
                    Phone: {{ $gr->purchaseOrder->supplier->phone ?? 'N/A' }}<br>
                    Email: {{ $gr->purchaseOrder->supplier->email ?? 'N/A' }}
                </address>
            </div>

            <div class="col-sm-4 invoice-col">
                <b>GRN ID: #{{ $gr->id }}</b><br>
                <b>PO Reference:</b> #{{ $gr->purchaseOrder->po_number ?? 'N/A' }}<br>
                <b>Status:</b> 
                <span class="badge {{ $gr->status == 'accepted' ? 'badge-success' : ($gr->status == 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                    {{ ucfirst($gr->status) }}
                </span><br>
                <b>Received Date:</b> {{ $gr->received_date ? \Carbon\Carbon::parse($gr->received_date)->format('d/m/Y H:i') : 'Pending' }}<br>
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
                        <th class="text-center">Ordered</th>
                        <th class="text-center">Received</th>
                        <th class="text-center">Accepted</th>
                        <th class="text-right">Unit Cost</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($gr->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @php
                                    $imageUrl = asset('assets/dist/img/default-150x150.png');
                                    if ($item->product->image) {
                                        if (filter_var($item->product->image, FILTER_VALIDATE_URL)) {
                                            $imageUrl = $item->product->image;
                                        } else {
                                            $imageUrl = asset('storage/' . $item->product->image);
                                        }
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" class="img-fluid rounded shadow-sm" style="max-height: 50px; width: 50px; object-fit: cover;">
                            </td>
                            <td><strong>{{ $item->product->name }}</strong></td>
                            <td><small class="text-muted font-weight-bold">{{ $item->product->sku }}</small></td>
                            <td class="text-center">{{ $item->ordered_qty }}</td>
                            <td class="text-center text-primary">{{ $item->received_qty }}</td>
                            <td class="text-center text-success font-weight-bold">{{ $item->accepted_qty }}</td>
                            <td class="text-right font-weight-bold">${{ number_format($item->unit_cost, 2) }}</td>
                            <td class="text-right font-weight-bold text-dark">${{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TOTAL --}}
        <div class="row mt-3">
            <div class="col-6">
                @if($gr->remarks)
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        <strong>Notes / Rejection Remarks:</strong><br>
                        {{ $gr->remarks }}
                    </p>
                @endif
                <div class="mt-4">
                    <p class="text-muted small">
                        * This document confirms that the above items have been inspected and received by our warehouse department.
                    </p>
                </div>
            </div>
            <div class="col-6">
                <table class="table">
                    <tr><th style="width:50%">Accepted Items Total</th><td class="text-right font-weight-bold">${{ number_format($total_amount, 2) }}</td></tr>
                    <tr><th>Processing Fee</th><td class="text-right">$0.00</td></tr>
                    <tr class="border-top">
                        <th><h5 class="mb-0">Net Received Value</h5></th>
                        <td class="text-right text-primary"><h5 class="mb-0 font-weight-bold">${{ number_format($total_amount, 2) }}</h5></td>
                    </tr>
                </table>
                <div class="text-center mt-4 pt-4 border-top">
                    <div style="width: 200px; border-bottom: 1px solid #ccc; margin: 0 auto;"></div>
                    <p class="mt-1 font-weight-bold">Warehouse Inspector Signature</p>
                </div>
            </div>
        </div>

    </div>

  {{-- ACTIONS --}}
  <div class="row no-print mt-3">
      <div class="col-12">
          @if(!request()->has('no_layout'))
            <a href="{{ route('goods_receiving.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
          @endif

          <button class="btn btn-default ml-2 shadow-sm" onclick="window.print()">
              <i class="fas fa-print mr-1"></i> Print Receipt
          </button>

          <button class="btn btn-primary float-right shadow-sm" onclick="window.print()">
              <i class="fas fa-file-pdf mr-1"></i> Export PDF
          </button>
      </div>
  </div>

</section>
</div>

@endsection
