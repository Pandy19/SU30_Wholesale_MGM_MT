@extends('backend.layouts.master')
@section('title', 'Returns & Disputes | Supplier Dashboard')
@section('main-content')

<div class="content-wrapper">
<section class="content">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0 text-dark font-weight-bold">Returns & Disputes</h3>
                    <p class="text-muted mb-0">Manage rejected items and provide resolutions</p>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('Supplier_Dashboard.index') }}">Supplier</a></li>
                        <li class="breadcrumb-item active">Disputes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="row mb-4 px-3">
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pending Disputes</span>
                    <span class="info-box-number">{{ $items->total() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger"><i class="fas fa-box-open"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Rejected Units</span>
                    <span class="info-box-number">{{ $items->sum('rejected_qty') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Value</span>
                    <span class="info-box-number">${{ number_format($items->sum('total_value'), 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card mx-3 shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <form action="{{ route('Supplier_Dashboard.disputes') }}" method="GET">
                <div class="input-group" style="width: 300px;">
                    <input type="text" name="search" class="form-control" placeholder="Search Product/SKU..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="pl-4">PO Number</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th class="text-center">Rejected Qty</th>
                        <th class="text-right">Value</th>
                        <th class="text-center">Status</th>
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
                        <td class="pl-4"><strong>{{ $po->po_number }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $imageUrl }}" class="rounded shadow-sm mr-2" width="35" height="35" style="object-fit: cover;">
                                <span>{{ $product->name }}</span>
                            </div>
                        </td>
                        <td><small class="text-muted">{{ $product->sku }}</small></td>
                        <td class="text-center"><span class="badge badge-danger">{{ $item->rejected_qty }}</span></td>
                        <td class="text-right font-weight-bold">${{ number_format($item->total_value, 2) }}</td>
                        <td class="text-center">
                            <span class="badge {{ $badgeClass }} px-2 py-1">{{ $status }}</span>
                        </td>
                        <td class="text-center">
                            @if($status === 'Pending')
                                <button class="btn btn-sm btn-success shadow-sm"
                                        onclick="openResolveModal({{ $item->id }}, {{ json_encode($product->name) }}, {{ $item->rejected_qty }}, {{ json_encode($imageUrl) }}, {{ json_encode($gr->remarks ?? '') }}, {{ json_encode($inspectorIdentity) }})">
                                    <i class="fas fa-check-circle mr-1"></i> Resolve
                                </button>
                            @else
                                <button class="btn btn-sm btn-warning shadow-sm"
                                        onclick="openResolveModal({{ $item->id }}, {{ json_encode($product->name) }}, {{ $item->rejected_qty }}, {{ json_encode($imageUrl) }}, {{ json_encode($gr->remarks ?? '') }}, {{ json_encode($inspectorIdentity) }}, {{ json_encode($status) }}, {{ json_encode($item->resolution_notes ?? '') }})">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No pending disputes found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0">
            <div class="float-right">
                {{ $items->links() }}
            </div>
        </div>
    </div>

</section>
</div>

<!-- RESOLVE DISPUTE MODAL -->
<div class="modal fade" id="resolveDisputeModal" tabindex="-1" role="dialog">
<div class="modal-dialog modal-dialog-centered">
    <form id="resolveForm" method="POST">
        @csrf
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div id="modal_header_div" class="modal-header text-white border-0 py-3">
                <h5 class="modal-title font-weight-bold">
                    <i id="modal_icon" class="fas fa-check-circle mr-2"></i>
                    <span id="modal_title_text">Resolve Dispute</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-4">
                <p class="text-muted small mb-4">Please review the inspection details and provide your official resolution below.</p>
                
                <!-- PRODUCT DETAILS CARD -->
                <div class="card border mb-4 shadow-sm" style="border-radius: 10px; background-color: #fcfcfc;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <img id="r_image" src="" class="rounded shadow-sm mr-3 border" width="70" height="70" style="object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="font-weight-bold mb-1" id="r_product" style="color: #2c3e50;"></h6>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-danger px-2 py-1 mr-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-times-circle mr-1"></i> <span id="r_qty"></span> Units Rejected
                                    </span>
                                    <small class="text-muted text-uppercase font-weight-bold" style="letter-spacing: 0.5px;">Official Record</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INSPECTION REASON -->
                <div class="mb-4">
                    <label class="font-weight-bold small text-uppercase text-muted" style="letter-spacing: 1px;">
                        <i class="fas fa-search-minus mr-1"></i> Warehouse Inspector Reason
                    </label>
                    <div class="p-3 bg-light rounded border-left" style="border-left: 4px solid #dee2e6 !important; color: #4a5568;">
                        <div class="small font-weight-bold mb-1 text-primary">Inspector: <span id="r_inspector_name"></span></div>
                        <div class="font-italic" id="r_inspector_remarks"></div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- RESOLUTION FORM -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark">Supplier Resolution <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-handshake text-muted"></i></span>
                        </div>
                        <select name="resolution" class="form-control border-left-0 shadow-none" style="border-radius: 0 5px 5px 0;" required>
                            <option value="" disabled selected>Select how to solve this...</option>
                            <option value="Returned to Supplier">Returned to Supplier</option>
                            <option value="Replaced by Supplier">Replaced by Supplier</option>
                            <option value="Refunded">Refunded</option>
                            <option value="Closed (Loss)">Closed (Loss)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="font-weight-bold text-dark">Resolution Notes & Evidence</label>
                    <textarea name="notes" class="form-control shadow-none" rows="3" 
                              style="border-radius: 8px; resize: none;"
                              placeholder="Describe the steps taken, tracking numbers, or refund references..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3">
                <button type="button" class="btn btn-link text-muted font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" id="submit_btn" class="btn px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
                    Confirm Resolution
                </button>
            </div>
        </div>
    </form>
</div>
</div>

@endsection

@push('scripts')
<script>
function openResolveModal(id, product, qty, imageUrl, remarks, inspectorIdentity, currentResolution = '', currentNotes = '') {
    document.getElementById('r_product').innerText = product;
    document.getElementById('r_qty').innerText = qty;
    document.getElementById('r_image').src = imageUrl;
    document.getElementById('r_inspector_name').innerText = inspectorIdentity || 'System Inspector';
    
    // Clean remarks: Remove the "Auto-generated..." prefix
    let cleanRemarks = remarks || 'No specific remarks provided by inspector.';
    cleanRemarks = cleanRemarks.replace(/Auto-generated from supplier delivery:\s*\|?\s*/i, '');
    document.getElementById('r_inspector_remarks').innerText = cleanRemarks;
    
    const form = document.getElementById('resolveForm');
    form.action = `/Supplier_Dashboard/dispute/${id}/resolve`;
    
    const resolutionSelect = form.querySelector('select[name="resolution"]');
    const notesTextarea = form.querySelector('textarea[name="notes"]');
    
    // Elements for dynamic styling
    const header = document.getElementById('modal_header_div');
    const titleText = document.getElementById('modal_title_text');
    const icon = document.getElementById('modal_icon');
    const submitBtn = document.getElementById('submit_btn');

    if (currentResolution) {
        // EDIT MODE
        resolutionSelect.value = currentResolution;
        titleText.innerText = 'Edit Dispute Resolution';
        header.classList.remove('bg-success');
        header.classList.add('bg-warning');
        icon.className = 'fas fa-edit mr-2';
        submitBtn.className = 'btn btn-warning px-4 font-weight-bold shadow-sm';
        submitBtn.innerText = 'Update Resolution';
    } else {
        // RESOLVE MODE
        resolutionSelect.selectedIndex = 0;
        titleText.innerText = 'Resolve Dispute';
        header.classList.remove('bg-warning');
        header.classList.add('bg-success');
        icon.className = 'fas fa-check-circle mr-2';
        submitBtn.className = 'btn btn-success px-4 font-weight-bold shadow-sm';
        submitBtn.innerText = 'Confirm Resolution';
    }
    
    notesTextarea.value = currentNotes;
    $('#resolveDisputeModal').modal('show');
}
</script>
@endpush
