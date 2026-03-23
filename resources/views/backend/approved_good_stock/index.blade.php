@extends('backend.layouts.master')
@section('title', 'Approved Goods | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content">

<!-- PAGE TITLE -->

 <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Approved Goods → Stock Approval</h3>
                    <p class="text-muted mb-0">
                        Add approved goods into stock (single warehouse)
                    </p>
                </div>
                
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Stock</a></li>
                        <li class="breadcrumb-item active">Approve Good</li>
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
        $totalApprovedQty = \App\Models\GoodsReceivingItem::sum('accepted_qty');
        $stockedQty = \App\Models\GoodsReceivingItem::sum('stocked_qty');
        $pendingStockQty = $totalApprovedQty - $stockedQty;
        
        // Calculate total value of pending items
        $highValuePending = 0;
        $allPending = \App\Models\GoodsReceivingItem::whereColumn('accepted_qty', '>', 'stocked_qty')->where('is_stocked', false)->get();
        foreach($allPending as $pItem) {
            $unit_cost = 0;
            if ($pItem->goodsReceiving && $pItem->goodsReceiving->purchase_order_id) {
                $poItem = \App\Models\PurchaseOrderItem::where('purchase_order_id', $pItem->goodsReceiving->purchase_order_id)
                                            ->where('product_id', $pItem->product_id)
                                            ->first();
                $unit_cost = $poItem ? $poItem->unit_cost : 0;
            }
            $highValuePending += (($pItem->accepted_qty - $pItem->stocked_qty) * $unit_cost);
        }
    @endphp

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success">
                <i class="fas fa-boxes"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Approved Units</span>
                <span class="info-box-number">{{ number_format($totalApprovedQty) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info">
                <i class="fas fa-hourglass-half"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Pending Stock Add (Units)</span>
                <span class="info-box-number">{{ number_format($pendingStockQty) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-secondary">
                <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Added to Stock (Units)</span>
                <span class="info-box-number">{{ number_format($stockedQty) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-danger">
                <i class="fas fa-exclamation-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">High Value Pending</span>
                <span class="info-box-number">${{ number_format($highValuePending, 2) }}</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-3 shadow-sm border-0">
<div class="card-body">
<form action="{{ route('approved_good_stock.index') }}" method="GET">
<div class="row">

    <div class="col-md-2">
        <input type="text" name="search" class="form-control"
               placeholder="Search Product / SKU" value="{{ request('search') }}">
    </div>

        <div class="col-md-2">
        <select class="form-control" name="category">
            <option value="">All Categories</option>
            @foreach(\App\Models\Category::orderBy('name')->get() as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control" name="brand">
            <option value="">All Brands</option>
            @foreach(\App\Models\Brand::orderBy('name')->get() as $brand)
                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control" name="supplier">
            <option value="">All Suppliers</option>
            @foreach(\App\Models\Supplier::orderBy('company_name')->get() as $sup)
                <option value="{{ $sup->id }}" {{ request('supplier') == $sup->id ? 'selected' : '' }}>{{ $sup->company_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control" name="storage_location">
            <option value="">All Locations</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}" {{ request('storage_location') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <button type="submit" class="btn btn-primary btn-block">Filter</button>
    </div>

</div>
</form>
</div>
</div>

<!-- ===================================================== -->
<!-- APPROVED GOODS TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>Image</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Brand</th>
    <th>Supplier</th>
    <th>Approved Qty</th>
    <th>Unit Cost</th>
    <th>Total Value</th>
    <th>PO No</th>
    <th>Approved By</th>
    <th>Approved Date</th>
    <th>Status</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

@forelse($items as $item)
@php
    $product = $item->product;
    $gr = $item->goodsReceiving;
    $po = $gr->purchaseOrder;
    $status = $item->is_stocked ? 'Added' : 'Pending';
    $badgeClass = $item->is_stocked ? 'badge-success' : 'badge-warning';
    $inspectorName = $gr->approver->name ?? 'System';
    $inspectorIdentity = $inspectorName . " [" . ucfirst($gr->approver->role ?? 'Inspector') . "]";

    // Image logic
    $imageUrl = $product->image ?? '';
    if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
        $imageUrl = asset('storage/' . $imageUrl);
    }
    if (!$imageUrl) {
        $imageUrl = asset('assets/dist/img/default-150x150.png');
    }
@endphp
<tr>
    <td class="text-center">
        <img src="{{ $imageUrl }}" width="50" class="rounded border shadow-sm">
    </td>
    <td>{{ $product->name }}</td>
    <td><small class="text-muted font-weight-bold">{{ $product->sku }}</small></td>
    <td>{{ $product->brand->name ?? 'N/A' }}</td>
    <td>{{ $po->supplier->company_name ?? 'N/A' }}</td>
    <td><strong>{{ $item->pending_qty }}</strong></td>
    <td class="text-right">${{ number_format($item->unit_cost, 2) }}</td>
    <td class="text-right font-weight-bold">${{ number_format($item->total_value, 2) }}</td>
    <td><strong>{{ $po->po_number }}</strong></td>

    <td><small>{{ $inspectorIdentity }}</small></td>
    <td><small>{{ $gr->received_date ? date('Y-m-d', strtotime($gr->received_date)) : 'N/A' }}</small></td>
    <td>
        <span class="badge {{ $badgeClass }}">{{ $status }}</span>
    </td>
    <td class="text-center">
        @if(!$item->is_stocked)
            <button class="btn btn-sm btn-success shadow-sm"
                onclick="openAddToStockModal({
                            id: {{ $item->id }},
                            name: {{ json_encode($product->name) }},
                            sku: {{ json_encode($product->sku) }},
                            po: {{ json_encode($po->po_number) }},
                            supplier: {{ json_encode($po->supplier->company_name ?? 'N/A') }},
                            brand: {{ json_encode($product->brand->name ?? 'N/A') }},
                            brandId: {{ $product->brand_id ?? 'null' }},
                            categoryId: {{ $product->category_id ?? 'null' }},
                            productId: {{ $product->id }},
                            approvedQty: {{ $item->pending_qty }},
                            unitCost: {{ $item->unit_cost }},
                            sellingPrice: {{ $product->selling_price ?? 0 }},
                            image: {{ json_encode($imageUrl) }},
                            selectedLocation: '{{ request('storage_location') }}'
                        })">
                Add to Stock
            </button>
        @else
            <i class="fas fa-check-double text-success"></i>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="13" class="text-center py-5 text-muted">No approved items found.</td>
</tr>
@endforelse

</tbody>
</table>

</div>

<!-- PAGINATION -->
<div class="card-footer clearfix bg-white">
    <div class="float-right">
        {{ $items->appends(request()->query())->links() }}
    </div>
</div>

</div>

</section>
<!-- Add to Stock Modal -->
<div class="modal fade" id="addToStockModal" tabindex="-1" role="dialog" aria-labelledby="addToStockModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="addToStockModalLabel">
          Add Approved Item to Stock
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <!-- Product summary -->
        <div class="media">
          <img id="m_image" src="" class="mr-3 rounded border" style="width:72px;height:72px;object-fit:cover;" alt="Product">
          <div class="media-body">
            <div class="d-flex align-items-start justify-content-between">
              <div>
                <h6 class="mb-1" id="m_name">—</h6>
                <div class="text-muted small">
                  SKU: <span id="m_sku">—</span> · PO: <span id="m_po">—</span>
                </div>
                <div class="text-muted small">
                  Brand: <span id="m_brand">—</span> · Supplier: <span id="m_supplier">—</span>
                </div>
              </div>

              <div class="text-right">
                <div class="small text-muted">Approved Qty</div>
                <div class="h5 mb-0" id="m_approvedQty">0</div>
              </div>
            </div>
          </div>
        </div>

        <hr class="my-3">

        <div class="row">
          <!-- Quantity -->
          <div class="col-md-3">
            <label class="mb-1">Add Quantity</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <button class="btn btn-outline-secondary" type="button" id="qtyMinus">
                  <i class="fas fa-minus"></i>
                </button>
              </div>

              <input type="number" class="form-control text-center" id="m_qty" value="1" min="1">

              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="qtyPlus">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>
            <small class="text-muted">
              Max: <span id="m_qtyMax">0</span>
            </small>
          </div>

          <!-- Selling Price -->
          <div class="col-md-3">
            <label class="mb-1">Selling Price <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="number" step="0.01" class="form-control" id="m_sellingPrice" placeholder="0.00">
            </div>
            <small class="text-muted">Required</small>
          </div>

          <!-- Storage location -->
          <div class="col-md-6">
            <label class="mb-1">
              Storage Location <span class="text-danger">*</span>
            </label>
            <select class="form-control" id="m_location">
              <option value="" selected disabled>Select matching shelf</option>
            </select>
            <small class="text-muted">
              Only shelves matching both <strong>Brand</strong> and <strong>Category</strong> are shown.
            </small>
          </div>
        </div>

        <div class="row mt-3">
          <!-- Notes -->
          <div class="col-md-8">
            <label class="mb-1">Notes (optional)</label>
            <textarea class="form-control" id="m_notes" rows="2" placeholder="E.g., Box condition, serial range, urgent placement..."></textarea>
          </div>

          <!-- Cost summary -->
          <div class="col-md-4">
            <div class="border rounded p-3 bg-light">
              <div class="d-flex justify-content-between small text-muted">
                <span>Unit Cost</span>
                <span id="m_unitCost">$0</span>
              </div>
              <div class="d-flex justify-content-between mt-1">
                <strong>Total</strong>
                <strong id="m_total">$0</strong>
              </div>
              <div class="small text-muted mt-2">
                This will be recorded as stock-in value.
              </div>
            </div>
          </div>
        </div>

        <!-- Inline validation message -->
        <div class="alert alert-warning mt-3 d-none" id="m_warn">
          Please select a storage location and ensure quantity is valid.
        </div>

        <!-- Hidden fields (optional) -->
        <input type="hidden" id="m_payload">
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
          Cancel
        </button>

        <button type="button" class="btn btn-success" id="m_confirmBtn">
          <span class="spinner-border spinner-border-sm mr-2 d-none" id="m_spinner"></span>
          Confirm Add to Stock
        </button>
      </div>

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
let activeItem = null;
const allLocations = @json($locations);
const filterLocId = "{{ request('storage_location') }}";

function openAddToStockModal(data) {
    activeItem = data;
    
    // Fill UI
    $('#m_image').attr('src', data.image);
    $('#m_name').text(data.name);
    $('#m_sku').text(data.sku);
    $('#m_po').text(data.po);
    $('#m_brand').text(data.brand);
    $('#m_supplier').text(data.supplier);
    $('#m_approvedQty').text(data.approvedQty);
    
    $('#m_qty').val(data.approvedQty).attr('max', data.approvedQty);
    $('#m_qtyMax').text(data.approvedQty);
    
    // Auto-calculate suggested selling price
    // Rule: < $1000 -> +10%, >= $1000 -> +15%
    let suggestedPrice = 0;
    if (data.unitCost < 1000) {
        suggestedPrice = data.unitCost * 1.10;
    } else {
        suggestedPrice = data.unitCost * 1.15;
    }
    $('#m_sellingPrice').val(suggestedPrice.toFixed(2));
    
    // Dynamically filter modal locations based on this item's brand/category
    const locationSelect = $('#m_location');
    locationSelect.empty().append('<option value="" selected disabled>Select matching shelf</option>');
    
    // STRICT RULE: Only show shelves matching Brand AND Category
    // Also, if a specific shelf was filtered at the top, only show that one if compatible.
    allLocations.forEach(loc => {
        const matchesBrand = (loc.brand_id == data.brandId);
        const matchesCategory = (loc.category_id == data.categoryId);
        const isCompatible = matchesBrand && matchesCategory;

        if (isCompatible) {
            // If user filtered by a specific location, only show that one
            if (filterLocId && loc.id != filterLocId) return;

            const isOccupiedByOther = (loc.current_product_id && loc.current_product_id != data.productId);
            const hasNoSpace = (loc.remaining_space < 1); // We can still show it but disable it
            
            const disabled = (isOccupiedByOther || hasNoSpace) ? 'disabled' : '';
            const selected = (filterLocId == loc.id || data.selectedLocation == loc.id) ? 'selected' : '';
            
            let label = `${loc.name} (${loc.remaining_space} left)`;
            if (isOccupiedByOther) label += ' - Occupied';
            else if (hasNoSpace) label += ' - Full';

            locationSelect.append(`<option value="${loc.id}" ${disabled} ${selected}>${label}</option>`);
        }
    });
    
    $('#m_unitCost').text('$' + data.unitCost.toLocaleString());
    updateTotal();
    
    $('#m_warn').addClass('d-none');
    $('#addToStockModal').modal('show');
}

function updateTotal() {
    if (!activeItem) return;
    const qty = parseInt($('#m_qty').val()) || 0;
    const total = qty * activeItem.unitCost;
    $('#m_total').text('$' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
}

$(document).ready(function() {
    // Check for success message after reload
    const successMsg = sessionStorage.getItem('stock_success_msg');
    if (successMsg) {
        Swal.fire({
            icon: 'success',
            title: 'Stock Updated!',
            text: successMsg,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            iconColor: '#28a745'
        });
        sessionStorage.removeItem('stock_success_msg');
    }

    $('#m_qty').on('input', function() {
        let val = parseInt($(this).val()) || 0;
        if (val > activeItem.approvedQty) { $(this).val(activeItem.approvedQty); val = activeItem.approvedQty; }
        if (val < 1) { $(this).val(1); val = 1; }
        updateTotal();
    });

    $('#qtyPlus').on('click', function() {
        let cur = parseInt($('#m_qty').val()) || 0;
        if (cur < activeItem.approvedQty) {
            $('#m_qty').val(cur + 1);
            updateTotal();
        }
    });

    $('#qtyMinus').on('click', function() {
        let cur = parseInt($('#m_qty').val()) || 0;
        if (cur > 1) {
            $('#m_qty').val(cur - 1);
            updateTotal();
        }
    });

    $('#m_confirmBtn').on('click', function() {
        const locId = $('#m_location').val();
        const qty = $('#m_qty').val();
        const sellingPrice = $('#m_sellingPrice').val();
        
        if (!locId || qty < 1 || !sellingPrice || sellingPrice <= 0) {
            $('#m_warn').text('Please select a storage location, enter a valid quantity, and set a selling price.').removeClass('d-none');
            return;
        }

        $('#m_warn').addClass('d-none');
        $('#m_spinner').removeClass('d-none');
        $(this).prop('disabled', true);

        $.ajax({
            url: "{{ route('approved_good_stock.add_to_stock') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                item_id: activeItem.id,
                location_id: locId,
                quantity: qty,
                selling_price: sellingPrice,
                notes: $('#m_notes').val()
            },
            success: function(response) {
                if (response.success) {
                    // Store message in session storage to show after reload
                    sessionStorage.setItem('stock_success_msg', `Successfully added ${response.qty} units of ${response.product_name} to stock.`);
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true
                    });
                    $('#m_spinner').addClass('d-none');
                    $('#m_confirmBtn').prop('disabled', false);
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error processing request. Please try again.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
                $('#m_spinner').addClass('d-none');
                $('#m_confirmBtn').prop('disabled', false);
            }
        });
    });
});
</script>
@endpush
