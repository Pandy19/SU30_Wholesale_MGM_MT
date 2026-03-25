@extends('backend.layouts.master')
@section('title', 'Stock Categorys | Wholesale MGM')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
   .select2-container--bootstrap4 .select2-selection--single {
      height: calc(2.25rem + 2px) !important;
   }
   .hover-shadow:hover {
      box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
      transition: box-shadow .3s ease-in-out;
   }
</style>
@endpush
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
                    <h3 class="m-0">Stock by Category</h3>
                    <p class="text-muted mb-0">
                        Warehouse stock grouped by category and brand
                    </p>
                </div>
                
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Stock</a></li>
                        <li class="breadcrumb-item active">Stock Categories</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info">
                <i class="fas fa-layer-group"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Categories</span>
                <span class="info-box-number">{{ $totalCategoriesCount }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success">
                <i class="fas fa-boxes"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Stock Qty</span>
                <span class="info-box-number">{{ number_format($totalStockQty) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Low Stock Items</span>
                <span class="info-box-number">{{ $lowStockItemsCount }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-secondary">
                <i class="fas fa-warehouse"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Warehouse</span>
                <span class="info-box-number">Main Store</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-4 shadow-sm">
<div class="card-body">
<form action="{{ route('stock_categorys.index') }}" method="GET">
<div class="row">

    <div class="col-md-3">
        <input type="text" id="stockSearch" name="search" class="form-control"
               placeholder="Search Product / SKU" value="{{ request('search') }}">
    </div>

    <div class="col-md-3">
        <select id="categoryFilter" name="category_id" class="form-control select2">
            <option value="">All Categories</option>
            @foreach($allCategories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <select id="brandFilter" name="brand_id" class="form-control select2">
            <option value="">All Brands</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" 
                        data-category="{{ $brand->category_id }}"
                        {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <select id="statusFilter" name="status" class="form-control select2">
            <option value="">All Status</option>
            <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal</option>
            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
        </select>
    </div>

</div>
</form>
</div>
</div>

<!-- ===================================================== -->
<!-- DYNAMIC CATEGORY LOOP -->
<!-- ===================================================== -->
@forelse($categories as $category)
<div class="card mb-4 shadow-sm category-card" data-category="{{ $category->id }}">

<a href="#category{{ $category->id }}" data-toggle="collapse"
   class="text-dark text-decoration-none">
<div class="card-header bg-light">
    <strong>{{ $category->name }}</strong>
    <span class="badge badge-info ml-2">{{ number_format($category->total_qty) }} Units</span>
    <span class="float-right">
        <i class="fas fa-chevron-down"></i>
    </span>
</div>
</a>

<div class="collapse show" id="category{{ $category->id }}">
<div class="card-body p-0">

    @foreach($category->brands_stock as $brand)
    <!-- ================= {{ strtoupper($brand->name) }} ================= -->
    <a href="#brand{{ $category->id }}_{{ $brand->id }}Stock" 
       class="text-dark text-decoration-none brand-card" 
       data-toggle="collapse"
       data-brand="{{ $brand->id }}"
       data-category="{{ $category->id }}"
       data-status="{{ $brand->has_low_stock ? 'low_stock' : 'normal' }}">
    <div class="card-body border-bottom bg-white hover-shadow">
    <div class="row align-items-center">
    <div class="col-md-1 text-center">
    @php
        $brandLogo = $brand->logo ?? '';
        if ($brandLogo && !filter_var($brandLogo, FILTER_VALIDATE_URL)) {
            $brandLogo = asset('storage/' . $brandLogo);
        }
        if (!$brandLogo) {
            $brandLogo = asset('assets/dist/img/default-150x150.png');
        }
    @endphp
    <img src="{{ $brandLogo }}" class="img-thumbnail" width="45" style="height:45px; object-fit:contain;">
    </div>
    <div class="col-md-6">
    <h5 class="mb-1">{{ $brand->name }}</h5>
    <small class="text-muted">Category: {{ $category->name }}</small>
    </div>
    <div class="col-md-3">
    @if($brand->has_low_stock)
        <span class="badge badge-warning mr-2">Low Stock Detected</span>
    @else
        <span class="badge badge-success mr-2">Normal</span>
    @endif
    <span class="badge badge-info">{{ number_format($brand->total_qty) }} Units</span>
    </div>
    <div class="col-md-2 text-right">
    <i class="fas fa-chevron-down"></i>
    </div>
    </div>
    </div>
    </a>

    <div class="collapse" id="brand{{ $category->id }}_{{ $brand->id }}Stock">
    <table class="table table-bordered mb-0">
    <thead class="thead-light">
    <tr>
    <th width="70">Image</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Description</th>
    <th width="80" class="text-center">Qty</th>
    <th width="110" class="text-right">Avg Cost</th>
    <th width="130" class="text-right">Total Value</th>
    <th width="300">Shelf / Location</th>
    </tr>
    </thead>
    <tbody>
    @foreach($brand->products as $product)
    @php
        $totalProdQty = $product->stocks->sum('quantity');
        $avgCost = $product->stocks->avg('average_cost') ?? 0;
        $totalProdValue = $totalProdQty * $avgCost;
        
        $productImg = $product->image ?? '';
        if ($productImg && !filter_var($productImg, FILTER_VALIDATE_URL)) {
            $productImg = asset('storage/' . $productImg);
        }
        if (!$productImg) {
            $productImg = asset('assets/dist/img/default-150x150.png');
        }
    @endphp
    <tr>
    <td class="text-center">
    <img src="{{ $productImg }}" class="img-thumbnail" width="45" style="height:45px; object-fit:contain;">
    </td>
    <td><strong>{{ $product->name }}</strong></td>
    <td><small class="text-muted font-weight-bold">{{ $product->sku }}</small></td>
    <td><small class="text-muted">{{ Str::limit($product->description ?? 'No description', 50) }}</small></td>
    <td class="text-center">
        <span class="badge {{ $totalProdQty < 5 ? 'badge-danger' : 'badge-light' }} h6 mb-0">
            {{ $totalProdQty }}
        </span>
    </td>
    <td class="text-right">${{ number_format($avgCost, 2) }}</td>
    <td class="text-right font-weight-bold">${{ number_format($totalProdValue, 2) }}</td>
    <td>
        @foreach($product->stocks as $stk)
            <div class="small mb-1">
                <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                {{ $stk->location->name ?? 'N/A' }} 
                <strong>({{ $stk->quantity }})</strong>
            </div>
        @endforeach
    </td>
    </tr>
    @endforeach
    </tbody>
    </table>
    </div>
    @endforeach

</div>
</div>
@empty
<div class="alert alert-info">
    <i class="fas fa-info-circle mr-2"></i> No stock found for the selected filters.
</div>
@endforelse

<!-- ===================================================== -->
<!-- PAGINATION -->
<!-- ===================================================== -->
<div class="mt-4">
    <x-pagination :data="$categories" />
</div>

</section>
</div>

@endsection

@section('scripts')
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
    
    const categoryCards = $('.category-card');
    const brandCards = $('.brand-card');

    function performFilter() {
        const searchText = stockSearch.val().toLowerCase();
        const selectedCat = categoryFilter.val();
        const selectedBrand = brandFilter.val();
        const selectedStatus = statusFilter.val();

        categoryCards.each(function() {
            const catCard = $(this);
            const catId = catCard.data('category').toString();
            let hasVisibleBrands = false;

            // Filter brands within this category
            const brandsInCat = catCard.find('.brand-card');
            
            brandsInCat.each(function() {
                const bCard = $(this);
                const bText = bCard.text().toLowerCase();
                const bId = bCard.data('brand').toString();
                const bCatId = bCard.data('category').toString();
                const bStatus = bCard.data('status');

                const matchesSearch = searchText === '' || bText.includes(searchText);
                const matchesCat = selectedCat === '' || bCatId === selectedCat;
                const matchesBrand = selectedBrand === '' || bId === selectedBrand;
                const matchesStatus = selectedStatus === '' || bStatus === selectedStatus;

                if (matchesSearch && matchesCat && matchesBrand && matchesStatus) {
                    bCard.show();
                    // Also show the table associated with the brand if it was open
                    // Or keep it hidden until clicked - standard collapse behavior
                    hasVisibleBrands = true;
                } else {
                    bCard.hide();
                    $(`#brand${bCatId}_${bId}Stock`).collapse('hide');
                }
            });

            // Show/Hide the whole category card based on whether it has visible brands
            if (hasVisibleBrands) {
                catCard.show();
            } else {
                catCard.hide();
            }
        });

        // Show "No data found" if all category cards are hidden
        if (categoryCards.filter(':visible').length === 0) {
            if ($('#noDataAlert').length === 0) {
                $('.content').append('<div id="noDataAlert" class="alert alert-info"><i class="fas fa-info-circle mr-2"></i> No stock matching your filters.</div>');
            }
        } else {
            $('#noDataAlert').remove();
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
});
</script>
@endsection
