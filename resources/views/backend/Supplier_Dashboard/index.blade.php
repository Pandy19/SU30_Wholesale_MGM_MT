@extends('backend.layouts.master')

@section('main-content')

<div class="content-wrapper">

    {{-- HEADER --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h1 class="m-0">Supplier Product Submission</h1>
                    <small class="text-muted">Click a product card to view full details</small>
                </div>
                <a href="{{ route('Supplier_Dashboard.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            </div>

            {{-- SEARCH & FILTER --}}
            <form action="{{ route('Supplier_Dashboard.index') }}" method="GET">
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by name, SKU or brand..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-default" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="limited" {{ request('status') == 'limited' ? 'selected' : '' }}>Limited</option>
                            <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                    </div>
                    @if(request('search') || (request('status') && request('status') != 'all'))
                        <div class="col-md-2">
                            <a href="{{ route('Supplier_Dashboard.index') }}" class="btn btn-link text-danger mt-1 p-0">
                                <i class="fas fa-times-circle"></i> Clear Filters
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-3">

                {{-- PRODUCT CARD --}}
                @forelse ($offers as $offer)
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">

                    <div class="card h-100 shadow-sm">

                        {{-- CLICKABLE AREA (DETAIL MODAL) --}}
                        <div class="product-click"
                             style="cursor:pointer;"
                             onclick="openSupplierDetailModal(this)"
                             data-image="{{ $offer->image ? asset('storage/' . $offer->image) : 'https://via.placeholder.com/600' }}"
                             data-title="{{ $offer->product_name }}"
                             data-brand="{{ $offer->brand_name }}"
                             data-category="{{ $offer->category_name }}"
                             data-status="{{ $offer->product_status }}"
                             data-supplier="{{ $supplier->company_name }}"
                             data-cost="{{ $offer->price }}"
                             data-qty="{{ $offer->available_qty }}"
                             data-specs="{{ e($offer->product_specs) }}"
                             data-description="{{ e($offer->product_description) }}">

                            <img
                                src="{{ $offer->image ? asset('storage/' . $offer->image) : 'https://via.placeholder.com/600' }}"
                                class="card-img-top p-2"
                                style="height:150px; object-fit:contain;"
                                alt="product"
                            >

                            <div class="card-body p-2">
                                <h6 class="font-weight-bold mb-1 text-truncate">{{ $offer->product_name }}</h6>
                                <small class="text-muted">{{ $offer->brand_name }} · {{ $offer->category_name }}</small>

                                <span class="badge {{ $offer->product_status == 'available' ? 'badge-success' : ($offer->product_status == 'limited' ? 'badge-warning' : 'badge-secondary') }} d-block my-1">
                                    {{ ucfirst($offer->product_status) }}
                                </span>

                                <small>
                                    Cost: ${{ number_format($offer->price, 2) }}<br>
                                    Qty: {{ $offer->available_qty }}
                                </small>
                            </div>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="card-footer p-1 text-center">
                            <a href="{{ route('Supplier_Dashboard.edit', $offer->id) }}" class="btn btn-warning btn-xs">
                                <i class="fas fa-edit"></i>
                            </a>

                            <button class="btn btn-danger btn-xs"
                                    onclick="event.stopPropagation(); openDeleteModal('{{ route('Supplier_Dashboard.offer.delete', $offer->id) }}');">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No products uploaded yet.</p>
                </div>
                @endforelse

            </div>

        </div>
    </section>
</div>

{{-- ================= PRODUCT DETAIL MODAL ================= --}}
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      {{-- HEADER --}}
      <div class="modal-header bg-white border-bottom">
        <div>
          <h5 class="modal-title font-weight-bold mb-0" id="detailTitle">Product Details</h5>
          <small class="text-muted" id="detailBrandCat">Brand · Category</small>
        </div>

        <div class="d-flex align-items-center">
          <span class="badge badge-pill px-3 py-2 mr-3" id="detailStatus">Available</span>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
      </div>

      {{-- BODY --}}
      <div class="modal-body">

        <div class="row">

          {{-- LEFT: IMAGE + QUICK INFO --}}
          <div class="col-lg-4 mb-3">

            <div class="card shadow-sm mb-3">
              <div class="card-body text-center">
                <img id="detailImg"
                     src=""
                     class="img-fluid"
                     style="max-height:320px;object-fit:contain;">
              </div>
            </div>

            <div class="card shadow-sm">
              <div class="card-body p-3">

                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted">Supplier</span>
                  <span class="font-weight-bold" id="detailSupplier">—</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted">Cost Price</span>
                  <span class="font-weight-bold">$<span id="detailCost">0</span></span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted">Available Qty</span>
                  <span class="font-weight-bold" id="detailQty">0</span>
                </div>

              </div>
            </div>

          </div>

          {{-- RIGHT: MAIN DETAILS --}}
          <div class="col-lg-8">

            {{-- TITLE STRIP --}}
            <div class="card shadow-sm mb-3">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <h4 class="font-weight-bold mb-1" id="detailName">Product Name</h4>
                    <div class="text-muted">
                      <i class="fas fa-tags mr-1"></i>
                      <span id="detailBrandText">Brand</span>
                      <span class="mx-2">•</span>
                      <span id="detailCategoryText">Category</span>
                    </div>
                  </div>

                  <div class="text-right">
                    <div class="text-muted small mb-1">Cost</div>
                    <div class="h5 font-weight-bold mb-0">
                      $<span id="detailCostBig">0</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

        {{-- DESCRIPTION --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white font-weight-bold p-2">
                <i class="fas fa-align-left mr-1 text-primary"></i> Description
            </div>
            <div class="card-body p-2">
                <div id="detailShortDesc" class="text-muted small">
                    {{-- Populated by JS --}}
                </div>
            </div>
        </div>

        {{-- SPECIFICATIONS (LONG TEXT) --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white font-weight-bold p-2">
                <i class="fas fa-list-ul mr-1 text-success"></i> Specifications
            </div>
            <div class="card-body p-0">
                <div class="p-3" style="background:#f8f9fa; min-height:100px;">
                    <pre class="mb-0"
                        style="white-space:pre-wrap;font-family:inherit;line-height:1.45;font-size:0.9rem;"
                        id="detailSpecs">{{-- Populated by JS --}}</pre>
                </div>
            </div>
        </div>

          </div>
        </div>

      </div>

      {{-- FOOTER --}}
      <div class="modal-footer bg-white">
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

{{-- ================= DELETE MODAL ================= --}}
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center p-4">

            <div class="mb-2">
                <i class="fas fa-times-circle text-danger" style="font-size:56px;"></i>
            </div>

            <h5 class="mb-1">Are you sure?</h5>
            <p class="text-muted mb-0">
                You want to delete this product offer.
            </p>

            <div class="mt-3">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm mr-2">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ================= SCRIPTS ================= --}}
<script>
    function openDeleteModal(url) {
        document.getElementById('deleteForm').action = url;
        $('#deleteModal').modal('show');
    }

    function openSupplierDetailModal(element) {
        console.log("Opening Supplier Detail Modal...");
        var btn = element;
        
        var image = btn.getAttribute('data-image');
        var title = btn.getAttribute('data-title');
        var brand = btn.getAttribute('data-brand');
        var category = btn.getAttribute('data-category');
        var status = btn.getAttribute('data-status');
        var supplier = btn.getAttribute('data-supplier');
        var cost = btn.getAttribute('data-cost');
        var qty = btn.getAttribute('data-qty');
        var specs = btn.getAttribute('data-specs');
        var description = btn.getAttribute('data-description');

        console.log("Detail data gathered:", {specs, description});
        
        document.getElementById('detailImg').src = image;
        document.getElementById('detailName').innerText = title;
        document.getElementById('detailBrandCat').innerText = brand + ' · ' + category;
        document.getElementById('detailStatus').innerText = status;
        
        // Update badge color
        var badge = document.getElementById('detailStatus');
        if(status === 'available') {
            badge.className = 'badge badge-success px-3 py-2 mr-3';
        } else if(status === 'limited') {
            badge.className = 'badge badge-warning px-3 py-2 mr-3';
        } else {
            badge.className = 'badge badge-secondary px-3 py-2 mr-3';
        }
        badge.innerText = status.charAt(0).toUpperCase() + status.slice(1);

        document.getElementById('detailSupplier').innerText = supplier;
        document.getElementById('detailCost').innerText = parseFloat(cost).toFixed(2);
        document.getElementById('detailCostBig').innerText = parseFloat(cost).toFixed(2);
        document.getElementById('detailQty').innerText = qty;

        document.getElementById('detailBrandText').innerText = brand;
        document.getElementById('detailCategoryText').innerText = category;
        
        // Desc & Specs
        document.getElementById('detailShortDesc').innerText = description ? description : 'No description provided.';
        document.getElementById('detailSpecs').innerText = specs ? specs : 'No specifications provided.';

        $('#detailModal').modal('show');
    }
</script>

@endsection
