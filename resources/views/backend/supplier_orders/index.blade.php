@extends('backend.layouts.master')
@section('title', 'Supplier Order Historys | Wholesale MGM')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
   .select2-container--bootstrap4 .select2-selection--single {
      height: calc(2.25rem + 2px) !important;
   }
</style>
@endpush
@section('main-content')

<div class="content-wrapper">
<section class="content">


  <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Suppliers – Invoice Product Preview</h1>
                    <p class="text-muted mb-0">
                        Click a brand row to view suppliers for each electronic brand.
                    </p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Supliers</a></li>
                        <li class="breadcrumb-item active">Supplier Order History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">
    <div class="col">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fas fa-file-invoice"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Orders</span>
                <span class="info-box-number">{{ $summary['total_orders'] }}</span>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Amount</span>
                <span class="info-box-number">${{ number_format($summary['total_amount'], 2) }}</span>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Paid Orders</span>
                <span class="info-box-number">{{ $summary['paid_orders'] }}</span>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fas fa-hourglass-half"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Partial Orders</span>
                <span class="info-box-number">{{ $summary['partial_orders'] }}</span>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="info-box">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-clock"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Unpaid Orders</span>
                <span class="info-box-number">{{ $summary['unpaid_orders'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-3">
<div class="card-body">
    <div class="row">
        <div class="col-md-3">
            <input type="text" id="poSearch" class="form-control shadow-sm"
                   placeholder="Search PO No (e.g. PO-0001)" value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select id="supplierFilter" class="form-control select2 shadow-xs">
                <option value="">All Suppliers</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select id="paymentFilter" class="form-control shadow-xs">
                <option value="">All Payment Status</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            </select>
        </div>

        <div class="col-md-3">
            <input type="date" id="dateFilter" class="form-control shadow-sm" value="{{ request('date') }}">
        </div>
    </div>
</div>
</div>

<!-- ===================================================== -->
<!-- INVOICE TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>PO No</th>
    <th>Supplier</th>
    <th>Order Date</th>
    <th>Invoice Date</th>
    <th>Total</th>
    <th>Payment</th>
    <th>Created By</th>

    <!-- NEW -->
    <th>Receiving Status</th>
    <th>Stock Status</th>

    <th class="text-center">Action</th>
</tr>
</thead>
<tbody id="poTableBody">

@forelse($purchase_orders as $po)
<tr class="po-row" 
    data-supplier="{{ $po->supplier_id }}" 
    data-payment="{{ strtolower($po->payment_status) }}"
    data-date="{{ $po->order_date ? $po->order_date->format('Y-m-d') : '' }}">
    <td>{{ $po->po_number }}</td>
    <td>{{ $po->supplier->company_name }}</td>
    <td>{{ $po->order_date ? $po->order_date->format('Y-m-d') : '-' }}</td>
    <td>{{ $po->created_at->format('Y-m-d') }}</td>
    <td>${{ number_format($po->total_amount, 2) }}</td>
    <td>
        @php
            $payStatus = strtolower($po->payment_status);
            $payBadge = $payStatus === 'paid' ? 'success' : ($payStatus === 'partial' ? 'info' : 'warning');
        @endphp
        <span class="badge badge-{{ $payBadge }}">{{ ucfirst($po->payment_status) }}</span>
    </td>
    <td>Admin</td>

    <!-- NEW -->
    <td>
        @php
            $status = strtolower($po->status);
            $statusBadge = [
                'pending' => 'info',
                'ordered' => 'primary',
                'received' => 'success',
                'completed' => 'success',
                'cancelled' => 'danger'
            ][$status] ?? 'secondary';
        @endphp
        <span class="badge badge-{{ $statusBadge }}">{{ ucfirst($po->status) }}</span>
    </td>
    <td>
        @if(in_array(strtolower($po->status), ['received', 'completed']))
            <span class="badge badge-success">Added to Stock</span>
        @else
            <span class="badge badge-warning">Not Added</span>
        @endif
    </td>

    <td class="text-center">
        <a href="{{ route('purchase_orders.confirm_payment', ['session_ids' => $po->id]) }}"
           class="btn btn-sm btn-primary">
            View Invoice
        </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="10" class="text-center py-4 text-muted">No purchase orders found.</td>
</tr>
@endforelse

</tbody>
</table>

</div>

<!-- ===================================================== -->
<!-- PAGINATION -->
<!-- ===================================================== -->
<div class="card-footer clearfix">
    <x-pagination :data="$purchase_orders" />
</div>

</div>

</section>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for the supplier filter
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    const poSearch = $('#poSearch');
    const supplierFilter = $('#supplierFilter');
    const paymentFilter = $('#paymentFilter');
    const dateFilter = $('#dateFilter');
    const poRows = $('.po-row');

    function performFilter() {
        const searchText = poSearch.val().toLowerCase();
        const selectedSupplier = supplierFilter.val();
        const selectedPayment = paymentFilter.val().toLowerCase();
        const selectedDate = dateFilter.val();

        poRows.each(function() {
            const row = $(this);
            const poNumber = row.find('td:first').text().toLowerCase();
            const supplierId = row.data('supplier');
            const paymentStatus = row.data('payment').toLowerCase();
            const orderDate = row.data('date');

            const matchesSearch = searchText === '' || poNumber.includes(searchText);
            const matchesSupplier = selectedSupplier === '' || supplierId == selectedSupplier;
            const matchesPayment = selectedPayment === '' || paymentStatus === selectedPayment;
            const matchesDate = selectedDate === '' || orderDate === selectedDate;

            if (matchesSearch && matchesSupplier && matchesPayment && matchesDate) {
                row.show();
            } else {
                row.hide();
            }
        });

        // Show "No data found" if all rows are hidden
        if (poRows.filter(':visible').length === 0) {
            if ($('#noDataRow').length === 0) {
                $('#poTableBody').append('<tr id="noDataRow"><td colspan="10" class="text-center py-4 text-muted">No purchase orders matching your filters.</td></tr>');
            }
        } else {
            $('#noDataRow').remove();
        }
    }

    poSearch.on('keyup', performFilter);
    supplierFilter.on('change', performFilter);
    paymentFilter.on('change', performFilter);
    dateFilter.on('change', performFilter);
});
</script>
@endsection
