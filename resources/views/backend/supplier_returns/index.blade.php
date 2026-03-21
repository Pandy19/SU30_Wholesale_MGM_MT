@extends('backend.layouts.master')
@section('title', 'Goods Return | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content">

<!-- ===================================================== -->
<!-- PAGE TITLE -->
<!-- ===================================================== -->

  <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Supplier Returns / Disputes</h1>
                    <p class="text-muted mb-0">
                    Track rejected products and supplier resolution
                    </p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Suppliers</a></li>
                        <li class="breadcrumb-item active">Good Return</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">
    @php
        $totalRejected = $items->sum('rejected_qty');
        $totalValue = $items->sum('total_value');
    @endphp
    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Disputes</span>
                <span class="info-box-number">{{ $items->total() }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-box"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Rejected Qty</span>
                <span class="info-box-number">{{ number_format($totalRejected) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-danger">
                <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Loss Value</span>
                <span class="info-box-number">${{ number_format($totalValue, 2) }}</span>
            </div>
        </div>
    </div>

</div>


<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-3 shadow-sm border-0">
<div class="card-body">
<form action="{{ route('supplier_returns.index') }}" method="GET">
<div class="row">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control"
               placeholder="Search PO / Product / SKU" value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary btn-block">Search</button>
    </div>
</div>
</form>
</div>
</div>

<!-- ===================================================== -->
<!-- DISPUTE TABLE -->
<!-- ===================================================== -->
<div class="card shadow-sm border-0">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="bg-light">
<tr>
    <th>Dispute ID</th>
    <th>PO No</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Brand</th>
    <th>Category</th>
    <th>Supplier</th>
    <th class="text-center">Rejected Qty</th>
    <th class="text-right">Total Value</th>
    <th>Status</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

@forelse($items as $item)
@php
    $gr = $item->goodsReceiving;
    $po = $gr->purchaseOrder;
    $product = $item->product;
    $status = $item->resolution_status ?? 'Pending';
    
    // Format Inspector Identity: Name [Role]
    $inspectorName = $gr->approver->name ?? 'System';
    $inspectorRole = $gr->approver->role ?? 'Inspector';
    $inspectorIdentity = $inspectorName . " [" . ucfirst($inspectorRole) . "]";

    $badgeClass = [
        'Pending' => 'badge-warning',
        'Returned to Supplier' => 'badge-info',
        'Replaced by Supplier' => 'badge-primary',
        'Refunded' => 'badge-success',
        'Closed (Loss)' => 'badge-secondary'
    ][$status] ?? 'badge-dark';

    // Product Image Logic
    $imageUrl = $product->image ?? '';
    if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
        $imageUrl = asset('storage/' . $imageUrl);
    }
    if (!$imageUrl) {
        $imageUrl = asset('assets/dist/img/default-150x150.png');
    }
@endphp
<tr>
    <td><small class="text-muted font-weight-bold">DSP-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</small></td>
    <td><strong>{{ $po->po_number ?? 'N/A' }}</strong></td>
    <td>{{ $product->name ?? 'N/A' }}</td>
    <td><small class="text-muted font-weight-bold">{{ $product->sku ?? 'N/A' }}</small></td>
    <td>{{ $product->brand->name ?? 'N/A' }}</td>
    <td>{{ $product->category->name ?? 'N/A' }}</td>
    <td>{{ $po->supplier->company_name ?? 'N/A' }}</td>
    <td class="text-center text-danger font-weight-bold">{{ $item->rejected_qty }}</td>
    <td class="text-right font-weight-bold">${{ number_format($item->total_value, 2) }}</td>
    <td><span class="badge {{ $badgeClass }} px-2 py-1 shadow-sm">{{ $status }}</span></td>
    <td class="text-center">
        <button class="btn btn-sm btn-primary shadow-sm px-3"
                onclick="viewDispute(
                    {{ $item->id }}, 
                    {{ json_encode($product->name) }}, 
                    {{ $item->rejected_qty }}, 
                    {{ json_encode($gr->remarks ?? '') }}, 
                    {{ json_encode($status) }}, 
                    {{ json_encode($item->resolution_notes ?? 'No notes provided yet.') }}, 
                    {{ json_encode($imageUrl) }}, 
                    {{ json_encode($inspectorIdentity) }},
                    {{ json_encode($gr->received_date ? date('M d, Y h:i A', strtotime($gr->received_date)) : 'N/A') }},
                    {{ json_encode($status !== 'Pending' ? $item->updated_at->format('M d, Y h:i A') : 'N/A') }}
                )">
            <i class="fas fa-eye mr-1"></i> View Details
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="11" class="text-center py-4 text-muted">No rejected items found.</td>
</tr>
@endforelse

</tbody>
</table>

</div>

<!-- ===================================================== -->
<!-- PAGINATION -->
<!-- ===================================================== -->
<div class="card-footer clearfix bg-white">
    <div class="float-right">
        {{ $items->appends(request()->query())->links() }}
    </div>
</div>

</div>

</section>
</div>

<!-- ===================================================== -->
<!-- VIEW DISPUTE MODAL -->
<!-- ===================================================== -->
<div class="modal fade" id="viewDisputeModal">
<div class="modal-dialog">
<div class="modal-content border-0 shadow-lg">
<div class="modal-header bg-dark text-white">
    <h5 class="modal-title font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Dispute Resolution Details</h5>
    <button class="close text-white" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <!-- PRODUCT HEADER -->
    <div class="d-flex align-items-start mb-4">
        <img id="v_image" src="" class="rounded shadow-sm mr-3 border" width="100" height="100" style="object-fit: cover;">
        <div class="flex-grow-1">
            <h5 class="font-weight-bold mb-1" id="v_product"></h5>
            <div class="text-danger font-weight-bold h3 mb-0">
                <span id="v_qty"></span> Units Rejected
            </div>
            <small class="text-muted"><i class="fas fa-barcode mr-1"></i>Official Dispute Record</small>
        </div>
    </div>
    
    <!-- TIMESTAMPS -->
    <div class="row mb-4">
        <div class="col-6">
            <div class="small text-muted font-weight-bold mb-1 text-uppercase">Rejection Date</div>
            <div class="p-2 bg-light border rounded small shadow-sm" id="v_reject_date"></div>
        </div>
        <div class="col-6">
            <div class="small text-muted font-weight-bold mb-1 text-uppercase">Resolution Date</div>
            <div class="p-2 bg-light border rounded small shadow-sm" id="v_resolve_date"></div>
        </div>
    </div>

    <!-- INSPECTION INFO -->
    <h6 class="font-weight-bold text-uppercase small text-primary mb-2"><i class="fas fa-search-minus mr-2"></i>Warehouse Inspection</h6>
    <div class="p-3 bg-light border rounded mb-4 shadow-sm">
        <div class="mb-1"><strong>Inspector:</strong> <span id="v_inspector" class="text-primary font-weight-bold"></span></div>
        <div class="mb-0 font-italic text-dark border-top pt-2 mt-2" id="v_reason"></div>
    </div>

    <!-- RESOLUTION INFO -->
    <h6 class="font-weight-bold text-uppercase small text-success mb-2"><i class="fas fa-handshake mr-2"></i>Supplier Resolution</h6>
    <div class="p-3 border rounded border-success shadow-sm" style="background-color: #fafffb;">
        <div class="mb-2"><strong>Current Status:</strong> <span id="v_status_badge" class="badge"></span></div>
        <div class="mb-1 font-weight-bold">Supplier Notes:</div>
        <div class="small text-dark p-2 bg-white rounded border" id="v_notes"></div>
    </div>
</div>
<div class="modal-footer bg-light border-0">
    <button class="btn btn-secondary shadow-sm px-4 font-weight-bold" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

@endsection

@push('scripts')
<script>
function viewDispute(id, product, qty, reason, status, notes, imageUrl, inspector, rejectDate, resolveDate) {
    document.getElementById('v_product').innerText = product;
    document.getElementById('v_qty').innerText = qty;
    
    // Clean reason: Remove the "Auto-generated..." prefix
    let cleanReason = reason || 'No specific reason provided by inspector.';
    cleanReason = cleanReason.replace(/Auto-generated from supplier delivery:\s*\|?\s*/i, '');
    document.getElementById('v_reason').innerText = cleanReason;

    document.getElementById('v_notes').innerText = notes || 'Waiting for supplier response...';
    document.getElementById('v_image').src = imageUrl;
    document.getElementById('v_inspector').innerText = inspector || 'System Inspector';
    document.getElementById('v_reject_date').innerText = rejectDate || 'N/A';
    document.getElementById('v_resolve_date').innerText = resolveDate || 'Pending';
    
    const badge = document.getElementById('v_status_badge');
    badge.innerText = status;
    badge.className = 'badge px-3 py-2 ';
    
    const badgeClasses = {
        'Pending': 'badge-warning',
        'Returned to Supplier': 'badge-info',
        'Replaced by Supplier': 'badge-primary',
        'Refunded': 'badge-success',
        'Closed (Loss)': 'badge-secondary'
    };
    badge.classList.add(badgeClasses[status] || 'badge-dark');

    $('#viewDisputeModal').modal('show');
}
</script>
@endpush
