@extends('backend.layouts.master')
@section('title', 'Purchase Orders | Wholesale MGM')
@section('page-title', 'Purchase Orders')

@push('styles')
<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
@endpush

@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

@if(!empty($grouped_cart))
    @php $grandTotal = 0; @endphp
    @foreach($grouped_cart as $supplier_id => $group)
        @php 
            $supplier = $group['supplier'];
            $items = $group['items'];
            $supplierSubtotal = 0;
            foreach($items as $item) {
                $supplierSubtotal += ($item['price'] * $item['qty']);
            }
            $grandTotal += $supplierSubtotal;
        @endphp

        {{-- ===================== SUPPLIER INVOICE: {{ $supplier->company_name }} ===================== --}}
        <div class="invoice p-3 mb-4 border rounded bg-white">

            {{-- HEADER --}}
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-0">
                        <i class="fas fa-globe"></i> Your Wholesale Co.
                        <small class="float-right">Date: {{ date('d/m/Y') }}</small>
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
                        <strong>{{ $supplier->company_name }}</strong><br>
                        {{ $supplier->address ?? 'N/A' }}<br>
                        Phone: {{ $supplier->phone ?? 'N/A' }}<br>
                        Email: {{ $supplier->email ?? 'N/A' }}
                    </address>
                </div>

                <div class="col-sm-4 invoice-col">
                    <b>Purchase Order #DRAFT-{{ $supplier->id }}</b><br>
                    <b>Account:</b> {{ $supplier->code }}<br>
                    <b>Payment Due:</b> <span class="display-payment-due">{{ date('d/m/Y', strtotime('+7 days')) }}</span><br>
                    <b>Payment Term:</b> <span class="display-payment-term">Net 30</span><br>
                    <b>Lead Time:</b> {{ $supplier->lead_time_days ?? 'N/A' }} Days
                </div>
            </div>

            {{-- ITEMS TABLE --}}
            <div class="row mt-3">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 80px;">Image</th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Description</th>
                            <th style="width: 100px;">Quantity</th>
                            <th style="width:140px">PO Qty</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <img src="{{ $item['image'] ?? asset('assets/dist/img/default-150x150.png') }}" 
                                     class="img-fluid rounded" style="max-height: 50px;">
                            </td>
                            <td>{{ $item['product_name'] }}</td>
                            <td>{{ $item['sku'] }}</td>
                            <td>{{ $item['description'] ?? '—' }}</td>
                            <td>
                                <span class="badge badge-info">{{ $item['available_qty'] ?? 0 }}</span>
                            </td>
                            <td>
                                <input type="number" class="form-control" value="{{ $item['qty'] }}" min="1" max="{{ $item['available_qty'] ?? 9999 }}">
                                <small class="text-muted">Code: {{ $supplier->code }}</small>
                            </td>
                            <td class="text-right font-weight-bold">${{ number_format($item['price'] * $item['qty'], 2) }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TOTALS --}}
            <div class="row mt-3">
                <div class="col-6"></div>
                <div class="col-6">
                    <table class="table">
                        <tr><th>Subtotal</th><td class="text-right">${{ number_format($supplierSubtotal, 2) }}</td></tr>
                        <tr><th>Tax (0%)</th><td class="text-right">$0.00</td></tr>
                        <tr><th>Total</th><td class="text-right"><strong>${{ number_format($supplierSubtotal, 2) }}</strong></td></tr>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

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
            <select class="form-control" id="inputPaymentMethod">
                <option>Cash</option>
                <option>Bank Transfer</option>
                <option>Digital Wallet</option>
            </select>
        </div>

        <div class="form-group">
            <label><strong>Payment Terms</strong></label>
            <select class="form-control" id="inputPaymentTerms">
                <option value="Immediate" data-days="0">Immediate</option>
                <option value="Net 7 Days" data-days="7">Net 7 Days</option>
                <option value="Net 15 Days" data-days="15">Net 15 Days</option>
                <option value="Net 30 Days" data-days="30" selected>Net 30 Days</option>
            </select>
        </div>

        <div class="form-group">
            <label><strong>Payment Status</strong></label>
            <select class="form-control" id="inputPaymentStatus">
                <option>Unpaid</option>
                <option>Partial</option>
                <option>Paid</option>
            </select>
        </div>

        <div class="form-group">
            <label><strong>Payment Reference / Note</strong></label>
            <textarea class="form-control" id="inputPaymentNote" rows="2"
                    placeholder="Transaction ID, cheque no, or internal note"></textarea>
        </div>
    </div>

    <div class="col-6">
    <p class="lead">Grand Total Summary</p>
    <table class="table">
    <tr><th>Cart Subtotal</th><td>${{ number_format($grandTotal, 2) }}</td></tr>
    <tr><th>Tax</th><td>$0.00</td></tr>
    <tr><th>Grand Total</th><td><strong>${{ number_format($grandTotal, 2) }}</strong></td></tr>
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
            <i class="far fa-check-circle"></i> Confirm All Purchase Orders
        </button>
    </div>
    </div>

@endif

</div>

</section>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Update Payment Terms and Due Date dynamically
    $('#inputPaymentTerms').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const termText = selectedOption.val();
        const days = parseInt(selectedOption.data('days'));
        
        // Update Payment Term text in all invoices
        $('.display-payment-term').text(termText);
        
        // Update Due Date in all invoices
        const today = new Date();
        today.setDate(today.getDate() + days);
        const dd = String(today.getDate()).padStart(2, '0');
        const mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
        const yyyy = today.getFullYear();
        const formattedDate = dd + '/' + mm + '/' + yyyy;
        
        $('.display-payment-due').text(formattedDate);
    });

    $('#confirmOrderBtn').on('click', function() {
        const paymentStatus = $('#inputPaymentStatus').val();
        const paymentMethod = $('#inputPaymentMethod').val();
        const paymentTerm = $('#inputPaymentTerms').val();
        const paymentNote = $('#inputPaymentNote').val();
        const paymentDue = $('.display-payment-due').first().text();
        
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Processing...');

        $.ajax({
            url: "{{ route('purchase_orders.store') }}",
            method: "POST",
            timeout: 10000, // 10 seconds timeout
            data: {
                _token: "{{ csrf_token() }}",
                payment_status: paymentStatus,
                payment_method: paymentMethod,
                payment_term: paymentTerm,
                remarks: paymentNote,
                due_date: paymentDue
            },
            success: function(response) {
                console.log("Success Response:", response);
                
                // If toastr exists, use it, otherwise redirect immediately
                if (typeof toastr !== 'undefined') {
                    toastr.success(response.message || "Order confirmed successfully!");
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1000);
                } else {
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr, status, error) {
                console.error("Error Response:", status, error, xhr);
                $btn.prop('disabled', false).html('<i class="far fa-check-circle"></i> Confirm All Purchase Orders');
                
                let message = 'Error processing request.';
                if (status === 'timeout') {
                    message = 'The request timed out. Please try again.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                if (typeof toastr !== 'undefined') {
                    toastr.error(message);
                } else {
                    alert(message);
                }
            }
        });
    });
});
</script>
@endsection
