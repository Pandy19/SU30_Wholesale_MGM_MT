@extends('backend.layouts.master')
@section('title', 'Good Receiving | Wholesale MGM')
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
                    <h3 class="m-0 font-weight-bold text-dark">Goods Receiving & Approval</h3>
                    <p class="text-muted mb-0">Verify and approve shipments to update warehouse stock</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Procurement</a></li>
                        <li class="breadcrumb-item active">Good Receiving</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

<!-- FILTERS -->
<div class="card mb-4 shadow-sm border-0">
<div class="card-body">
<form action="{{ route('goods_receiving.index') }}" method="GET">
    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
    <div class="row">
        <div class="col-md-3 mb-2">
            <input type="text" id="grSearch" name="search" class="form-control shadow-sm"
                   placeholder="Search Product / SKU / PO No" value="{{ request('search') }}">
        </div>

        <div class="col-md-2 mb-2">
            <select id="categoryFilter" name="category_id" class="form-control select2 shadow-xs">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 mb-2">
            <select id="brandFilter" name="brand_id" class="form-control select2 shadow-xs">
                <option value="">All Brands</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 mb-2">
            <select id="supplierFilter" name="supplier_id" class="form-control select2 shadow-xs">
                <option value="">All Suppliers</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-2">
            <select id="statusFilter" name="status" class="form-control shadow-xs">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
    </div>
</form>
</div>
</div>

<!-- GOODS RECEIVING TABLE -->
<div class="card shadow-sm border-0">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0 align-middle">
<thead class="bg-light">
<tr>
    <th class="text-center">Image</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Brand</th>
    <th>Supplier</th>
    <th class="text-center">Ordered</th>
    <th class="text-center">Received</th>
    <th class="text-center" style="width: 100px;">Accept</th>
    <th class="text-center" style="width: 100px;">Reject</th>
    <th class="text-right">Unit Cost</th>
    <th class="text-right">Total</th>
    <th>Status</th>
    <th>Approved By</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

@forelse($items as $item)
@php
    $gr = $item->goodsReceiving;
    $product = $item->product;
    $status = strtolower($gr->status ?? 'pending');
    $category_id = $product->category_id ?? '';
    $brand_id = $product->brand_id ?? '';
    $supplier_id = $gr->purchaseOrder->supplier_id ?? '';
    $po_number = $gr->purchaseOrder->po_number ?? '';
@endphp
<tr class="gr-item-row" 
    data-category="{{ $category_id }}" 
    data-brand="{{ $brand_id }}" 
    data-supplier="{{ $supplier_id }}" 
    data-status="{{ $status }}"
    data-po="{{ $po_number }}">
    <td class="text-center">
        @php
            $imageUrl = $product->image ?? '';
            if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $imageUrl = asset('storage/' . $imageUrl);
            }
            if (!$imageUrl) {
                $imageUrl = asset('assets/dist/img/default-150x150.png');
            }
        @endphp
        <img src="{{ $imageUrl }}" class="img-thumbnail shadow-sm" width="50" height="50" style="object-fit: cover;">
    </td>
    <td><strong>{{ $product->name ?? 'N/A' }}</strong></td>
    <td><small class="text-muted font-weight-bold">{{ $product->sku ?? 'N/A' }}</small></td>
    <td>{{ $product->brand->name ?? 'N/A' }}</td>
    <td>{{ $gr->purchaseOrder->supplier->company_name ?? 'N/A' }}</td>
    <td class="text-center font-weight-bold">{{ $item->ordered_qty }}</td>
    <td class="text-center font-weight-bold text-primary">{{ $item->received_qty }}</td>

    <td class="text-center">
        @if($status === 'pending' && $item->accepted_qty == 0 && $item->rejected_qty == 0)
            <input type="number"
                   id="accept_qty_{{ $item->id }}"
                   class="form-control form-control-sm text-center font-weight-bold border-success accept-input"
                   data-id="{{ $item->id }}"
                   data-received="{{ $item->received_qty }}"
                   value="{{ $item->received_qty }}" min="0" max="{{ $item->received_qty }}">
        @else
            <span class="text-success font-weight-bold h6">{{ $item->accepted_qty }}</span>
        @endif
    </td>

    <td class="text-center">
        @if($status === 'pending' && $item->accepted_qty == 0 && $item->rejected_qty == 0)
            <input type="number"
                   id="reject_qty_{{ $item->id }}"
                   class="form-control form-control-sm text-center font-weight-bold border-danger reject-input"
                   data-id="{{ $item->id }}"
                   value="0" min="0" max="{{ $item->received_qty }}">
        @else
            <span class="text-danger font-weight-bold h6">{{ $item->rejected_qty }}</span>
        @endif
    </td>

    <td class="text-right font-weight-bold text-dark">${{ number_format($item->unit_cost, 2) }}</td>
    <td class="text-right font-weight-bold text-dark">${{ number_format($item->total_cost, 2) }}</td>

    <td>
        @php
            $itemProcessed = ($item->accepted_qty > 0 || $item->rejected_qty > 0);
            
            if (!$itemProcessed) {
                $displayStatus = 'pending';
                $badgeClass = 'badge-info';
                $statusLabel = 'Pending';
            } else {
                if ($item->rejected_qty > 0 && $item->accepted_qty == 0) {
                    $displayStatus = 'rejected';
                    $badgeClass = 'badge-danger';
                    $statusLabel = 'Rejected';
                } elseif ($item->accepted_qty > 0 && $item->rejected_qty == 0) {
                    $displayStatus = 'accepted';
                    $badgeClass = 'badge-success';
                    $statusLabel = 'Approved goods received';
                } else {
                    $displayStatus = 'partially_accepted';
                    $badgeClass = 'badge-warning';
                    $statusLabel = 'Partially Approved';
                }
            }
        @endphp
        <span class="badge {{ $badgeClass }} px-2 py-1 shadow-sm">{{ $statusLabel }}</span>
    </td>
    <td class="{{ !($gr && $gr->approver) ? 'text-muted italic' : 'font-weight-bold' }}">
        {{ $gr->approver->name ?? '—' }}
    </td>

<td class="text-center">
    @if($status === 'pending' && $item->accepted_qty == 0 && $item->rejected_qty == 0)
        <button type="button" 
                class="btn btn-sm btn-success shadow-sm px-3"
                onclick="handleProcess({{ $item->id }}, {{ $item->goods_receiving_id }})">
            Process / Approve
        </button>
        
        <!-- HIDDEN FORM FOR PROCESSING -->
        <form id="processForm_{{ $item->id }}" 
              action="{{ route('goods_receiving.approve', $item->goods_receiving_id) }}" 
              method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <input type="hidden" name="accepted_qty_{{ $item->id }}" id="form_accept_{{ $item->id }}">
            <input type="hidden" name="rejected_qty_{{ $item->id }}" id="form_reject_{{ $item->id }}">
            <input type="hidden" name="remarks" id="form_remarks_{{ $item->id }}">
        </form>
    @else
        <button class="btn btn-sm btn-outline-secondary shadow-sm"
                onclick="printReceipt('{{ $status }}','{{ $gr->approver->name ?? 'Admin' }}','{{ $gr->received_date }}')">
            <i class="fas fa-print"></i> Print
        </button>
    @endif
</td>
</tr>
@empty
<tr>
    <td colspan="14" class="text-center py-5 text-muted">
        <i class="fas fa-box-open fa-3x mb-3 d-block opacity-25"></i>
        No goods receiving items found.
    </td>
</tr>
@endforelse

</tbody>
</table>

</div>

<!-- PAGINATION -->
<div class="card-footer clearfix bg-white">
    <x-pagination :data="$items" />
</div>

</div>

</section>
</div>

<!-- REJECT REASON MODAL -->
<div class="modal fade" id="rejectReasonModal" tabindex="-1" role="dialog">
<div class="modal-dialog">
<div class="modal-content border-0 shadow-lg">

<div class="modal-header bg-danger text-white">
    <h5 class="modal-title font-weight-bold">
        <i class="fas fa-exclamation-triangle mr-2"></i> Rejection Reason
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
</div>

<div class="modal-body">
    <p class="text-dark">You have entered a <strong>Rejected Quantity</strong>. Please provide a reason for the warehouse records.</p>
    <div class="form-group">
        <label class="font-weight-bold">Remarks / Rejection Notes</label>
        <textarea id="modal_remarks" class="form-control" rows="3"
                  placeholder="e.g., Damaged items, Wrong SKU, etc." required></textarea>
    </div>
</div>

<div class="modal-footer bg-light border-0">
    <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">Cancel</button>
    <button type="button" onclick="confirmAndSubmit()" class="btn btn-danger shadow-sm font-weight-bold px-4">Confirm & Process</button>
</div>

</div>
</div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
let currentActiveId = null;

function handleProcess(itemId, grId) {
    const acceptInput = document.getElementById('accept_qty_' + itemId);
    const rejectInput = document.getElementById('reject_qty_' + itemId);
    
    if (!acceptInput || !rejectInput) return;

    const acceptQty = parseInt(acceptInput.value) || 0;
    const rejectQty = parseInt(rejectInput.value) || 0;

    // Set values in the hidden form
    document.getElementById('form_accept_' + itemId).value = acceptQty;
    document.getElementById('form_reject_' + itemId).value = rejectQty;
    
    currentActiveId = itemId;

    // If there's any rejection, show the modal
    if (rejectQty > 0) {
        $('#modal_remarks').val(''); 
        $('#rejectReasonModal').modal('show');
    } else {
        document.getElementById('processForm_' + itemId).submit();
    }
}

function confirmAndSubmit() {
    const remarks = document.getElementById('modal_remarks').value;
    if (!remarks || remarks.trim() === '') {
        alert('Please provide a reason for rejection.');
        return;
    }
    
    document.getElementById('form_remarks_' + currentActiveId).value = remarks;
    document.getElementById('processForm_' + currentActiveId).submit();
    $('#rejectReasonModal').modal('hide');
}

function printReceipt(status, approver, date) {
    window.print();
}

$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    const grSearch = $('#grSearch');
    const categoryFilter = $('#categoryFilter');
    const brandFilter = $('#brandFilter');
    const supplierFilter = $('#supplierFilter');
    const statusFilter = $('#statusFilter');
    const grRows = $('.gr-item-row');

    function performFilter() {
        const searchText = grSearch.val().toLowerCase();
        const selectedCat = categoryFilter.val();
        const selectedBrand = brandFilter.val();
        const selectedSupplier = supplierFilter.val();
        const selectedStatus = statusFilter.val().toLowerCase();

        grRows.each(function() {
            const row = $(this);
            const productText = row.find('td:nth-child(2)').text().toLowerCase();
            const skuText = row.find('td:nth-child(3)').text().toLowerCase();
            const poNumber = row.data('po').toString().toLowerCase();
            
            const catId = row.data('category').toString();
            const brandId = row.data('brand').toString();
            const supplierId = row.data('supplier').toString();
            const status = row.data('status').toString().toLowerCase();

            const matchesSearch = searchText === '' || 
                                 productText.includes(searchText) || 
                                 skuText.includes(searchText) || 
                                 poNumber.includes(searchText);
            
            const matchesCat = selectedCat === '' || catId === selectedCat;
            const matchesBrand = selectedBrand === '' || brandId === selectedBrand;
            const matchesSupplier = selectedSupplier === '' || supplierId === selectedSupplier;
            const matchesStatus = selectedStatus === '' || status === selectedStatus;

            if (matchesSearch && matchesCat && matchesBrand && matchesSupplier && matchesStatus) {
                row.show();
            } else {
                row.hide();
            }
        });

        // Show "No data found" if all rows are hidden
        if (grRows.filter(':visible').length === 0) {
            if ($('#noDataRow').length === 0) {
                $('tbody').append('<tr id="noDataRow"><td colspan="14" class="text-center py-5 text-muted">No goods receiving items matching your filters.</td></tr>');
            }
        } else {
            $('#noDataRow').remove();
        }
    }

    grSearch.on('keyup', performFilter);
    categoryFilter.on('change', performFilter);
    brandFilter.on('change', performFilter);
    supplierFilter.on('change', performFilter);
    statusFilter.on('change', performFilter);

    // Auto-sync quantities logic
    $(document).on('input', '.accept-input', function() {
        let id = $(this).data('id');
        let received = $(this).data('received');
        let val = parseInt($(this).val()) || 0;
        
        if (val > received) { val = received; $(this).val(received); }
        if (val < 0) { val = 0; $(this).val(0); }
        
        $('#reject_qty_' + id).val(received - val);
    });

    $(document).on('input', '.reject-input', function() {
        let id = $(this).data('id');
        let received = $('#accept_qty_' + id).data('received');
        let val = parseInt($(this).val()) || 0;
        
        if (val > received) { val = received; $(this).val(received); }
        if (val < 0) { val = 0; $(this).val(0); }
        
        $('#accept_qty_' + id).val(received - val);
    });
});
</script>
@endpush
