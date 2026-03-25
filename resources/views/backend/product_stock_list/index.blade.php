@extends('backend.layouts.master')
@section('title', 'Stock Lists | Wholesale MGM')
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

<!-- PAGE TITLE -->

 <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Product Stock List</h3>
                    <p class="text-muted mb-0">
                    Product stock overview based on applied filters
                    </p>
                </div>
                
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Stock</a></li>
                        <li class="breadcrumb-item active">Stock Lists</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
<!-- ===================================================== -->
<!-- LOW STOCK ALERT (UI ONLY) -->
<!-- ===================================================== -->
@if($lowStockCount > 0)
<div class="alert alert-warning d-flex align-items-center mb-3">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    <div>
        <strong>Low Stock Alert:</strong>
        There are {{ $lowStockCount }} products currently below minimum stock level (10 units).
        Please review and reorder soon.
    </div>
</div>
@endif

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info">
                <i class="fas fa-box"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Products</span>
                <span class="info-box-number">{{ number_format($totalProducts) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success">
                <i class="fas fa-layer-group"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Units</span>
                <span class="info-box-number">{{ number_format($totalUnits) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Low Stock (Units < 10)</span>
                <span class="info-box-number">{{ number_format($lowStockCount) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-secondary">
                <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Stock Value</span>
                <span class="info-box-number">${{ number_format($totalStockValue, 2) }}</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-3 shadow-sm border-0">
<div class="card-body">
<form action="{{ route('product_stock_list.index') }}" method="GET">
<div class="row">

    <div class="col-md-3">
        <input type="text" id="stockSearch" name="search" class="form-control"
               placeholder="Search Product / SKU" value="{{ request('search') }}">
    </div>

    <div class="col-md-2">
        <select id="categoryFilter" name="category_id" class="form-control select2">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select id="brandFilter" name="brand_id" class="form-control select2">
            <option value="">All Brands</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" 
                        data-category="{{ $brand->category_id }}"
                        {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select id="statusFilter" name="status" class="form-control select2">
            <option value="">All Stock Status</option>
            <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal Stock</option>
            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
        </select>
    </div>

    <div class="col-md-2">
        <select id="locationFilter" name="location_id" class="form-control select2">
            <option value="">All Shelves</option>
            @foreach($locations as $loc)
                <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-1 text-right">
        <a href="{{ route('product_stock_list.index') }}" class="btn btn-outline-secondary w-100">
            <i class="fas fa-undo mr-1"></i>Reset
        </a>
    </div>

</div>
</form>
</div>
</div>

<!-- ===================================================== -->
<!-- PRODUCT STOCK TABLE -->
<!-- ===================================================== -->
<div class="card shadow-sm border-0">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="bg-light">
<tr>
    <th width="70" class="text-center">Image</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Main Supplier</th>
    <th width="90" class="text-center">Qty</th>
    <th width="120" class="text-right">Unit Cost</th>

    <th width="120" class="text-right">Selling Price</th>
    <th width="120" class="text-right">Profit / Unit</th>

    <th width="130" class="text-right">Stock Value</th>
    <th width="200">Shelf / Location</th>
    <th width="120" class="text-center">Stock Status</th>
    <th width="80" class="text-center">Action</th>
</tr>
</thead>
<tbody>

@forelse($products as $product)
@php
    $qty = $product->total_qty ?? 0;
    $avgCost = $product->avg_cost ?? 0;
    $sellingPrice = $product->selling_price ?? 0;
    $profit = $sellingPrice - $avgCost;
    $stockValue = $qty * $avgCost;
    
    $statusLabel = 'Normal';
    $badgeClass = 'badge-success';
    $rowClass = '';
    $dataStatus = 'normal';
    
    if ($qty <= 0) {
        $statusLabel = 'Out of Stock';
        $badgeClass = 'badge-danger';
        $rowClass = 'table-danger';
        $dataStatus = 'out_of_stock';
    } elseif ($qty < 10) {
        $statusLabel = 'Low Stock';
        $badgeClass = 'badge-warning';
        $rowClass = 'table-warning';
        $dataStatus = 'low_stock';
    }

    $imageUrl = $product->image ?? '';
    if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
        $imageUrl = asset('storage/' . $imageUrl);
    }
    if (!$imageUrl) {
        $imageUrl = asset('assets/dist/img/default-150x150.png');
    }

    $locationIds = $product->stocks->pluck('stock_location_id')->unique()->join(',');
@endphp
<tr class="product-stock-row {{ $rowClass }}" 
    data-category="{{ $product->category_id }}" 
    data-brand="{{ $product->brand_id }}" 
    data-status="{{ $dataStatus }}" 
    data-locations="{{ $locationIds }}">
    <td class="text-center">
        <img src="{{ $imageUrl }}" class="img-thumbnail" width="45" style="height:45px; object-fit:contain;">
    </td>
    <td>
        <strong>{{ $product->name }}</strong><br>
        <small class="text-muted">{{ $product->brand->name ?? 'N/A' }} · {{ $product->category->name ?? 'N/A' }}</small>
    </td>
    <td><small class="text-muted font-weight-bold">{{ $product->sku }}</small></td>
    <td>{{ $product->suppliers->first()->company_name ?? 'N/A' }}</td>
    <td class="text-center"><strong>{{ number_format($qty) }}</strong></td>

    <td class="text-right">${{ number_format($avgCost, 2) }}</td>

    <td class="text-right"><strong>${{ number_format($sellingPrice, 2) }}</strong></td>
    <td class="text-right text-{{ $profit >= 0 ? 'success' : 'danger' }}">
        <strong>${{ number_format($profit, 2) }}</strong>
    </td>

    <td class="text-right font-weight-bold">${{ number_format($stockValue, 2) }}</td>
    <td>
        @forelse($product->stocks as $stock)
            <div class="small">
                <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                {{ $stock->location->name ?? 'N/A' }} ({{ $stock->quantity }})
            </div>
        @empty
            <small class="text-muted">No specific location</small>
        @endforelse
    </td>
    <td class="text-center"><span class="badge {{ $badgeClass }} shadow-sm px-2 py-1">{{ $statusLabel }}</span></td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-primary shadow-sm"
                onclick='openProductDetailModal(@json($product), {{ $qty }}, {{ $avgCost }}, {{ $stockValue }}, "{{ $statusLabel }}", "{{ $badgeClass }}", "{{ $imageUrl }}")'>
            <i class="fas fa-eye"></i>
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="12" class="text-center py-5 text-muted">No products found in stock list.</td>
</tr>
@endforelse

</tbody>
</table>

<div class="card-footer clearfix bg-white">
    <x-pagination :data="$products" />
</div>
</div>
</div>




<!-- ===================================================== -->
<!-- PRODUCT DETAIL MODAL (MODERN REDESIGN) -->
<!-- ===================================================== -->
<div class="modal fade" id="productDetailModal" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content border-0 shadow-lg">

    <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title font-weight-bold text-dark">
            <i class="fas fa-info-circle text-primary mr-2"></i>Product Stock Details
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body pt-2">
        <!-- TOP SECTION: IDENTITY -->
        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded shadow-xs">
            <div class="mr-4">
                <img id="m_image" src="" class="img-fluid rounded border bg-white shadow-sm" 
                     style="width:120px; height:120px; object-fit:contain;">
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 id="m_name" class="font-weight-bold mb-1 text-dark">Product Name</h3>
                        <p class="text-muted mb-2">
                            <span class="badge badge-outline-secondary border px-2 py-1">SKU: <span id="m_sku" class="font-weight-bold"></span></span>
                            <span class="mx-1 text-light">|</span>
                            <span id="m_brand" class="font-weight-bold"></span>
                            <span class="mx-1 text-light">·</span>
                            <span id="m_category" class="text-muted"></span>
                        </p>
                    </div>
                    <span id="m_status" class="badge px-3 py-2 shadow-sm" style="font-size: 0.9rem;">Stock Status</span>
                </div>
            </div>
        </div>

        <!-- MIDDLE SECTION: KEY METRICS -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="border rounded p-3 text-center bg-white shadow-xs h-100">
                    <small class="text-uppercase text-muted font-weight-bold d-block mb-2" style="letter-spacing: 0.5px;">Current Stock</small>
                    <h4 id="m_qty" class="font-weight-bold mb-0 text-primary">—</h4>
                    <small class="text-muted">Units Available</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center bg-white shadow-xs h-100">
                    <small class="text-uppercase text-muted font-weight-bold d-block mb-2" style="letter-spacing: 0.5px;">Unit Cost</small>
                    <h4 id="m_cost" class="font-weight-bold mb-0 text-dark">—</h4>
                    <small class="text-muted">Average Price</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center bg-white shadow-xs h-100">
                    <small class="text-uppercase text-muted font-weight-bold d-block mb-2" style="letter-spacing: 0.5px;">Selling Price</small>
                    <h4 id="m_selling" class="font-weight-bold mb-0 text-success">—</h4>
                    <small class="text-muted">Retail Target</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center bg-white shadow-xs h-100">
                    <small class="text-uppercase text-muted font-weight-bold d-block mb-2" style="letter-spacing: 0.5px;">Profit / Unit</small>
                    <h4 id="m_profit" class="font-weight-bold mb-0">—</h4>
                    <small class="text-muted">Per Item</small>
                </div>
            </div>
        </div>

        <!-- BOTTOM SECTION: SECONDARY DETAILS -->
        <div class="row">
            <div class="col-md-7">
                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-white py-2">
                        <small class="text-uppercase font-weight-bold text-muted">Specifications & Description</small>
                    </div>
                    <div class="card-body bg-light p-3">
                        <div id="m_description" style="max-height:150px; overflow-y:auto; line-height: 1.6; color: #444; font-size: 0.95rem;">
                            Description goes here.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-white py-2">
                        <small class="text-uppercase font-weight-bold text-muted">Logistics Info</small>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2 border-bottom-0">
                            <span class="text-muted small">Main Supplier</span>
                            <span id="m_supplier" class="font-weight-bold small text-dark">—</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2 border-bottom-0">
                            <span class="text-muted small">Stock Value</span>
                            <span id="m_value" class="font-weight-bold small text-primary">—</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2 border-bottom-0">
                            <span class="text-muted small">Total Profit</span>
                            <span id="m_total_profit" class="font-weight-bold small text-success">—</span>
                        </li>
                        <li class="list-group-item py-2">
                            <span class="text-muted small d-block mb-1">Storage Locations</span>
                            <div id="m_locations" class="p-2 bg-light rounded border-dashed">
                                <!-- Locations injected here -->
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light border-top-0">
        <button class="btn btn-outline-secondary px-4 shadow-sm" data-dismiss="modal">Close Details</button>
    </div>

</div>
</div>

<style>
    .border-dashed { border: 1px dashed #ced4da; }
    .shadow-xs { box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .badge-outline-secondary { color: #6c757d; border-color: #6c757d; background: transparent; }
</style>

@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    const stockSearch = $('#stockSearch');
    const categoryFilter = $('#categoryFilter');
    const brandFilter = $('#brandFilter');
    const statusFilter = $('#statusFilter');
    const locationFilter = $('#locationFilter');
    const stockRows = $('.product-stock-row');

    function performFilter() {
        const searchText = stockSearch.val().toLowerCase();
        const selectedCat = categoryFilter.val();
        const selectedBrand = brandFilter.val();
        const selectedStatus = statusFilter.val();
        const selectedLocation = locationFilter.val();

        stockRows.each(function() {
            const row = $(this);
            const rowText = row.text().toLowerCase();
            const catId = row.data('category').toString();
            const brandId = row.data('brand').toString();
            const status = row.data('status');
            const locations = row.data('locations').toString().split(',');

            const matchesSearch = searchText === '' || rowText.includes(searchText);
            const matchesCat = selectedCat === '' || catId === selectedCat;
            const matchesBrand = selectedBrand === '' || brandId === selectedBrand;
            const matchesStatus = selectedStatus === '' || status === selectedStatus;
            const matchesLocation = selectedLocation === '' || locations.includes(selectedLocation);

            if (matchesSearch && matchesCat && matchesBrand && matchesStatus && matchesLocation) {
                row.show();
            } else {
                row.hide();
            }
        });

        // Show "No data found" if all rows are hidden
        if (stockRows.filter(':visible').length === 0) {
            if ($('#noDataRow').length === 0) {
                $('tbody').append('<tr id="noDataRow"><td colspan="12" class="text-center py-5 text-muted">No products matching your filters.</td></tr>');
            }
        } else {
            $('#noDataRow').remove();
        }
    }

    stockSearch.on('keyup', performFilter);
    
    categoryFilter.on('change', function() {
        const catId = $(this).val();
        
        // Synchronize Brand Dropdown
        brandFilter.val(''); 
        
        if (catId) {
            brandFilter.find('option').each(function() {
                const opt = $(this);
                const optCat = opt.data('category');
                if (opt.val() === '' || optCat == catId) {
                    opt.show();
                } else {
                    opt.hide();
                }
            });
        } else {
            brandFilter.find('option').show();
        }
        
        if (brandFilter.hasClass('select2-hidden-accessible')) {
            brandFilter.trigger('change.select2');
        }
        
        performFilter();
    });

    brandFilter.on('change', performFilter);
    statusFilter.on('change', performFilter);
    locationFilter.on('change', performFilter);
});

function openProductDetailModal(product, qty, avgCost, stockValue, statusLabel, badgeClass, imageUrl) {
    $('#m_image').attr('src', imageUrl);
    $('#m_name').text(product.name);
    $('#m_sku').text(product.sku);
    $('#m_brand').text(product.brand ? product.brand.name : 'N/A');
    $('#m_category').text(product.category ? product.category.name : 'N/A');
    
    $('#m_status').text(statusLabel).removeClass('badge-success badge-warning badge-danger').addClass(badgeClass);
    
    const supplier = product.suppliers && product.suppliers.length > 0 ? product.suppliers[0].company_name : 'N/A';
    $('#m_supplier').text(supplier);
    $('#m_qty').text(qty.toLocaleString() + ' Units');
    $('#m_cost').text('$' + avgCost.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    $('#m_selling').text('$' + (product.selling_price || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    $('#m_value').text('$' + stockValue.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    
    const profitPerUnit = (product.selling_price || 0) - avgCost;
    $('#m_profit').text('$' + profitPerUnit.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}))
        .removeClass('text-success text-danger').addClass(profitPerUnit >= 0 ? 'text-success' : 'text-danger');

    // Total Potential Profit
    const totalPotentialProfit = profitPerUnit * qty;
    $('#m_total_profit').text('$' + totalPotentialProfit.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}))
        .removeClass('text-success text-danger').addClass(totalPotentialProfit >= 0 ? 'text-success' : 'text-danger');

    let locations = '';
    if (product.stocks && product.stocks.length > 0) {
        product.stocks.forEach(stk => {
            locations += `<div class="small">${stk.location ? stk.location.name : 'N/A'} (${stk.quantity})</div>`;
        });
    } else {
        locations = '<small class="text-muted">No stock location</small>';
    }
    $('#m_locations').html(locations);
    
    $('#m_description').html(product.description || '<span class="text-muted">No description provided.</span>');

    $('#productDetailModal').modal('show');
}
</script>
@endpush
