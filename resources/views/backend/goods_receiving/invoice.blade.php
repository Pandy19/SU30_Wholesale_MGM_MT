@extends(request()->has('no_layout') ? 'backend.layouts.blank' : 'backend.layouts.master')
@section('title', 'Goods Receiving Note | Wholesale MGM')
@section('main-content')

<div class="{{ request()->has('no_layout') ? '' : 'content-wrapper' }}">
<section class="content {{ request()->has('no_layout') ? 'p-0' : 'p-4' }}" style="{{ request()->has('no_layout') ? 'overflow-x: hidden;' : '' }}">

    {{-- ===================== GOODS RECEIVING INVOICE (Same Design as confirm_payment) ===================== --}}
    <div class="invoice p-3 mb-4 border rounded bg-white">

        {{-- HEADER --}}
        <div class="row">
            <div class="col-12">
                <h4 class="mb-0">
                    <i class="fas fa-globe"></i> Your Wholesale Co.
                    <small class="float-right">Date: {{ $gr->received_date ? \Carbon\Carbon::parse($gr->received_date)->format('d/m/Y') : 'N/A' }}</small>
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
                    <strong>{{ $gr->purchaseOrder->supplier->company_name ?? 'N/A' }}</strong><br>
                    {{ $gr->purchaseOrder->supplier->address ?? 'N/A' }}<br>
                    Phone: {{ $gr->purchaseOrder->supplier->phone ?? 'N/A' }}<br>
                    Email: {{ $gr->purchaseOrder->supplier->email ?? 'N/A' }}
                </address>
            </div>

            <div class="col-sm-4 invoice-col">
                <b>Goods Receiving Note #{{ $gr->id }}</b><br>
                <b>Purchase Order:</b> #{{ $gr->purchaseOrder->po_number ?? 'N/A' }}<br>
                <b>Status:</b> <span class="badge {{ $gr->status == 'accepted' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($gr->status) }}</span><br>
                <b>Approved By:</b> {{ $gr->approver->name ?? 'N/A' }}<br>
                <b>Received Date:</b> {{ $gr->received_date ? \Carbon\Carbon::parse($gr->received_date)->format('d/m/Y') : '—' }}<br>
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
                        <th class="text-center">Qty Received</th>
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
                                <img src="{{ $imageUrl }}" class="img-fluid rounded" style="max-height: 50px;">
                            </td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->product->sku }}</td>
                            <td class="text-center">{{ $item->accepted_qty }}</td>
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
                @if($gr->remarks)
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        <strong>Note:</strong> {{ $gr->remarks }}
                    </p>
                @endif
                <p class="text-muted well well-sm shadow-none">
                    <strong>Payment Reference:</strong> Linked to PO #{{ $gr->purchaseOrder->po_number ?? 'N/A' }}
                </p>
            </div>
            <div class="col-6">
                <table class="table">
                    <tr><th style="width:50%">Subtotal</th><td class="text-right">${{ number_format($total_amount, 2) }}</td></tr>
                    <tr><th>Tax (0%)</th><td class="text-right">$0.00</td></tr>
                    <tr class="border-top"><th>Total</th><td class="text-right"><strong>${{ number_format($total_amount, 2) }}</strong></td></tr>
                </table>
            </div>
        </div>

    </div>

</section>
</div>

@endsection
