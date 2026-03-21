@extends('backend.layouts.master')
@section('title', 'Product Management | Wholesale MGM')
@section('main-content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0">Supplier Products</h1>
               <p class="text-muted mb-0">Review products offered by suppliers and choose where to buy.</p>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Suppliers</a></li>
                  <li class="breadcrumb-item active">Product Management</li>
               </ol>
            </div>
         </div>
      </div>
   </div>
   <section class="content">
      <div class="container-fluid">
         <div class="row mb-3">
            <div class="col-md-3">
               <select class="form-control" id="categoryFilter" onchange="filterProducts()">
                  <option value="">All Categories</option>
                  @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                  @endforeach
               </select>
            </div>
            <div class="col-md-3">
               <select class="form-control" id="brandFilter" onchange="filterProducts()">
                  <option value="">All Brands</option>
                  @foreach($brands as $brand)
                  <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                  @endforeach
               </select>
            </div>
            <div class="col-md-5">
               <input type="text" class="form-control" id="searchInput" placeholder="Search product name or SKU" value="{{ request('search') }}" onkeyup="if(event.key === 'Enter') filterProducts()">
            </div>
            <div class="col-md-1">
               <button class="btn btn-warning btn-block position-relative" data-toggle="modal" data-target="#cartModal">
               <i class="fas fa-shopping-cart"></i>
               <span class="badge badge-danger navbar-badge" style="position: absolute; top: -5px; right: -5px;">0</span>
               </button>
            </div>
         </div>
         <div class="card">
            <div class="card-body p-0">
               <table class="table table-bordered table-hover mb-0">
                  <thead class="thead-light">
                     <tr>
                        <th width="70">Image</th>
                        <th>Product</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Suppliers</th>
                        <th>Best Price</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($products as $p)
                     <tr data-brand="{{ strtolower($p->brand_name ?? '') }}"
                        data-category="{{ strtolower($p->category_name ?? '') }}">
                        <td class="text-center">
                           <img
                              src="{{ !empty($p->image_url) ? $p->image_url : asset('images/no-image.png') }}"
                              class="img-fluid rounded"
                              style="max-height:60px;"
                              >
                        </td>
                        <td>
                           <strong>{{ $p->product_name }}</strong><br>
                           <small class="text-muted">SKU: {{ $p->sku }}</small>
                        </td>
                        <td>{{ $p->brand_name ?: '-' }}</td>
                        <td>{{ $p->category_name ?: '-' }}</td>
                        <td>
                           <span class="badge badge-info">
                           {{ (int)$p->supplier_count }} {{ ((int)$p->supplier_count) === 1 ? 'Supplier' : 'Suppliers' }}
                           </span>
                        </td>
                        <td>
                           @if($p->best_price !== null)
                           ${{ number_format($p->best_price, 0) }}
                           @else
                           —
                           @endif
                        </td>
                        <td>
                           @php
                           $s = strtolower($p->status ?? '');
                           $badge = $s === 'available' ? 'success' : ($s === 'limited' ? 'warning' : 'secondary');
                           @endphp
                           <span class="badge badge-{{ $badge }}">{{ $p->status }}</span>
                        </td>
                        <td class="text-center">
                           <button class="btn btn-sm btn-success btn-open-supplier-modal" 
                              data-product-id="{{ $p->product_id }}">
                           <i class="fas fa-cart-plus"></i>
                           </button>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                           No data found.
                        </td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
               <!-- ================= PAGINATION (UI ONLY) ================= -->
               <div class="card-footer clearfix">
                  <ul class="pagination pagination-sm m-0 float-right">
                     <li class="page-item disabled">
                        <a class="page-link" href="#">«</a>
                     </li>
                     <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                     </li>
                     <li class="page-item">
                        <a class="page-link" href="#">2</a>
                     </li>
                     <li class="page-item">
                        <a class="page-link" href="#">3</a>
                     </li>
                     <li class="page-item">
                        <a class="page-link" href="#">»</a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@include('backend.product_management.purchase')

{{-- ================= ADD TO CART SUCCESS MODAL ================= --}}
<div class="modal fade" id="addToCartModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-md modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body text-center p-4">
            <div class="mb-3">
               <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                  style="width:72px;height:72px;background:#e9f7ef;">
                  <i class="fas fa-check text-success" style="font-size:34px;"></i>
               </div>
            </div>
            <h4 class="font-weight-bold mb-1">Added to Cart</h4>
            <p class="text-muted mb-3">Item has been added successfully.</p>
            <div class="border rounded p-3 text-left bg-light">
               <div class="d-flex justify-content-between">
                  <span class="text-muted">Product</span>
                  <span class="font-weight-bold" id="cartModalProduct">—</span>
               </div>
               <div class="d-flex justify-content-between mt-2">
                  <span class="text-muted">SKU</span>
                  <span id="cartModalSKU">—</span>
               </div>
               <div class="d-flex justify-content-between mt-2">
                  <span class="text-muted">Supplier</span>
                  <span id="cartModalSupplier">—</span>
               </div>
               <div class="d-flex justify-content-between mt-2">
                  <span class="text-muted">Cost</span>
                  <span class="font-weight-bold text-primary">
                  $<span id="cartModalPrice">0</span>
                  </span>
               </div>
            </div>
            <div class="mt-4 d-flex justify-content-center">
               <button class="btn btn-outline-secondary mr-2" data-dismiss="modal">
               Continue Shopping
               </button>
               <button type="button" class="btn btn-primary" onclick="openCartAfterAdded()">
               <i class="fas fa-shopping-cart mr-1"></i> View Cart
               </button>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('scripts')
<script>
function filterProducts() {
    const category = document.getElementById('categoryFilter').value;
    const brand = document.getElementById('brandFilter').value;
    const search = document.getElementById('searchInput').value;
    
    let url = new URL(window.location.href);
    if(category) url.searchParams.set('category', category); else url.searchParams.delete('category');
    if(brand) url.searchParams.set('brand', brand); else url.searchParams.delete('brand');
    if(search) url.searchParams.set('search', search); else url.searchParams.delete('search');
    
    window.location.href = url.toString();
}

document.addEventListener('click', async function (e) {
  // 1. OPEN SUPPLIER MODAL
  const openBtn = e.target.closest('.btn-open-supplier-modal');
  if (openBtn) {
    const productId = openBtn.getAttribute('data-product-id');

    // reset placeholders
    document.getElementById('modalProductImage').src = "{{ asset('images/no-image.png') }}";
    document.getElementById('modalProductName').textContent = '—';
    document.getElementById('modalBrandName').textContent = '—';
    document.getElementById('modalProductSKU').textContent = '—';
    document.getElementById('modalProductSpecs').textContent = '—';

    document.getElementById('modalSupplierTable').innerHTML =
        `<tr><td colspan="9" class="text-center text-muted py-3">Loading...</td></tr>`;

    document.getElementById('modalPriceHistory').innerHTML =
        `<tr><td colspan="4" class="text-center text-muted py-3">Loading...</td></tr>`;

    // open modal first
    $('#supplierSelectModal').modal('show');

    try {
        const res = await fetch(`/product_management/${productId}/details`);
        const data = await res.json();

        if (!res.ok) {
            throw new Error(data.message || 'Error loading details');
        }

        // product header
        document.getElementById('modalProductName').textContent = data.product.product_name ?? '—';
        document.getElementById('modalBrandName').textContent = data.product.brand_name ?? '—';
        document.getElementById('modalProductSKU').textContent = data.product.sku ?? '—';
        document.getElementById('modalProductSpecs').textContent = data.product.specs_text ?? '—';

        if (data.product.image_url) {
            document.getElementById('modalProductImage').src = data.product.image_url;
        }

        // suppliers table
        if (!data.suppliers || data.suppliers.length === 0) {
            document.getElementById('modalSupplierTable').innerHTML =
                `<tr><td colspan="9" class="text-center text-muted py-3">No suppliers found for this product</td></tr>`;
        } else {
            document.getElementById('modalSupplierTable').innerHTML = data.suppliers.map(s => `
                <tr class="${s.is_best_price ? 'table-success' : ''}">
                <td>
                    ${s.company_name ?? ''}
                    ${s.is_best_price ? '<br><span class="badge badge-success"><i class="fas fa-award mr-1"></i>Best Price</span>' : ''}
                </td>
                <td>${s.supplier_code ?? ''}</td>
                <td>${s.phone ?? ''}</td>
                <td>${s.email ?? ''}</td>
                <td class="font-weight-bold ${s.is_best_price ? 'text-success' : 'text-primary'}">
                    $${Number(s.cost_price ?? 0).toLocaleString()}
                </td>
                <td><span class="badge badge-info">${s.available_qty ?? 0}</span></td>
                <td>${s.lead_time ?? '—'} days</td>
                <td><span class="badge badge-success">${s.status ?? 'Active'}</span></td>
                <td class="text-center">
                    <button class="btn btn-sm ${s.is_best_price ? 'btn-success' : 'btn-warning'} font-weight-bold btn-add-to-cart"
                    data-product-id="${s.product_id}"
                    data-supplier-id="${s.supplier_id}"
                    data-product="${data.product.product_name ?? ''}"
                    data-supplier="${s.company_name ?? ''}"
                    data-price="${s.cost_price ?? 0}"
                    data-sku="${data.product.sku ?? ''}">
                    <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                    </button>
                </td>
                </tr>
            `).join('');
        }

        // price history table
        if (!data.price_history || data.price_history.length === 0) {
            document.getElementById('modalPriceHistory').innerHTML =
                `<tr><td colspan="4" class="text-center text-muted py-3">—</td></tr>`;
        } else {
            document.getElementById('modalPriceHistory').innerHTML = data.price_history.map(h => `
                <tr>
                <td>${h.supplier_name ?? ''}</td>
                <td>$${Number(h.purchase_price ?? 0).toLocaleString()}</td>
                <td>${h.purchase_date ?? ''}</td>
                <td>${h.last_updated ?? ''}</td>
                </tr>
            `).join('');
        }

    } catch (err) {
        document.getElementById('modalSupplierTable').innerHTML =
            `<tr><td colspan="9" class="text-center text-danger py-3">${err.message}</td></tr>`;
        document.getElementById('modalPriceHistory').innerHTML =
            `<tr><td colspan="4" class="text-center text-danger py-3">—</td></tr>`;
    }
    return;
  }

  // 2. ADD TO CART
  const addBtn = e.target.closest('.btn-add-to-cart');
  if (addBtn) {
    const productId = addBtn.getAttribute('data-product-id');
    const supplierId = addBtn.getAttribute('data-supplier-id');
    const price = addBtn.getAttribute('data-price');
    const qty = 1;

    try {
        const res = await fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId, supplier_id: supplierId, price, qty })
        });
        
        const data = await res.json();
        if (res.ok) {
            $('#supplierSelectModal').modal('hide');
            
            document.getElementById('cartModalProduct').textContent = addBtn.getAttribute('data-product');
            document.getElementById('cartModalSKU').textContent = addBtn.getAttribute('data-sku');
            document.getElementById('cartModalSupplier').textContent = addBtn.getAttribute('data-supplier');
            document.getElementById('cartModalPrice').textContent = Number(price).toLocaleString();
            $('#addToCartModal').modal('show');
            
            if (typeof loadCart === 'function') loadCart();
        } else {
            alert(data.message || 'Error adding to cart');
        }
    } catch (err) {
        console.error('Error adding to cart:', err);
    }
    return;
  }
});
</script>
@endsection
