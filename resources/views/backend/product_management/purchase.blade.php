<div class="modal fade" id="supplierSelectModal" tabindex="-1">
   <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="fas fa-truck mr-1"></i> Choose Supplier</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
         </div>

         <div class="modal-body">

            <div class="card mb-3">
               <div class="card-body">
                  <div class="row align-items-center">
                     <div class="col-md-2 text-center">
                        <img id="modalProductImage"
                             src="{{ asset('images/no-image.png') }}"
                             class="img-fluid rounded">
                     </div>
                     <div class="col-md-10">
                        <h5 class="mb-1" id="modalProductName">—</h5>
                        <small class="text-muted">
                            Brand: <span id="modalBrandName">—</span> |
                            SKU: <span id="modalProductSKU">—</span>
                        </small>
                     </div>
                  </div>
               </div>
            </div>

            <div class="card mb-3">
               <div class="card-header"><strong>Available Suppliers</strong></div>
               <div class="card-body p-0">
                  <table class="table table-bordered table-hover mb-0">
                     <thead class="thead-light">
                        <tr>
                           <th>Supplier</th>
                           <th>Code</th>
                           <th>Phone</th>
                           <th>Email</th>
                           <th>Cost Price</th>
                           <th>Available Qty</th>
                           <th>Lead Time</th>
                           <th>Status</th>
                           <th width="140">Select</th>
                        </tr>
                     </thead>
                     <tbody id="modalSupplierTable">
                        <tr>
                           <td colspan="9" class="text-center text-muted py-3">
                              Select a product to load suppliers
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>

            <div class="card mb-3">
               <div class="card-header">
                  <strong><i class="fas fa-history mr-1"></i> Supplier Price History</strong>
               </div>
               <div class="card-body p-0">
                  <table class="table table-sm table-bordered mb-0">
                     <thead class="thead-light">
                        <tr>
                           <th>Supplier</th>
                           <th>Purchase Price</th>
                           <th>Purchase Date</th>
                           <th>Last Updated</th>
                        </tr>
                     </thead>
                     <tbody id="modalPriceHistory">
                        <tr>
                           <td colspan="4" class="text-center text-muted py-3">—</td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>

            <div class="card">
               <div class="card-header">
                  <strong><i class="fas fa-align-left mr-1"></i> Product Specifications</strong>
               </div>
               <div class="card-body p-0">
                  <pre id="modalProductSpecs"
                       class="border-0 rounded-0 p-3 bg-light mb-0"
                       style="max-height: 400px; overflow-y: auto; font-size: 13px;">—</pre>
               </div>
            </div>

         </div>

         <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal">Close</button>
         </div>

      </div>
   </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
         <div class="modal-header bg-warning">
            <h5 class="modal-title" id="cartModalLabel">
               <i class="fas fa-shopping-cart mr-2"></i> Current Purchase Order Draft
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body p-0">
            <div id="cartContent" class="p-3">
               <!-- Dynamic Cart Content Goes Here -->
               <div class="text-center py-5">
                  <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                  <p class="text-muted">Your purchase order draft is empty.</p>
               </div>
            </div>
         </div>
         <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Continue Shopping</button>
            <a href="{{ route('purchase_orders.index') }}" class="btn btn-primary" id="btnProceedToPO" style="display:none;">
            <i class="fas fa-check-circle mr-1"></i> Proceed to Purchase Orders
            </a>
         </div>
      </div>
   </div>
</div>

<script>
// Load Cart Data
async function loadCart() {
    try {
        const res = await fetch('{{ route("cart.get") }}');
        const data = await res.json();
        renderCart(data);
    } catch (err) {
        console.error('Error loading cart:', err);
    }
}

function renderCart(data) {
    const cartContent = document.getElementById('cartContent');
    const btnProceed = document.getElementById('btnProceedToPO');
    
    if (!data.grouped || Object.keys(data.grouped).length === 0) {
        cartContent.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <p class="text-muted">Your purchase order draft is empty.</p>
            </div>`;
        btnProceed.style.display = 'none';
        updateCartBadge(0);
        return;
    }

    btnProceed.style.display = 'inline-block';
    updateCartBadge(data.total_qty);

    let html = '';
    for (const supplierId in data.grouped) {
        const group = data.grouped[supplierId];
        html += `
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-light d-flex align-items-center justify-content-between">
                    <div>
                        <strong><i class="fas fa-truck mr-1 text-primary"></i> ${group.supplier_name}</strong>
                        <span class="badge badge-primary ml-2">${group.supplier_code}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="bg-white">
                            <tr>
                                <th style="width:50px;">#</th>
                                <th style="width:260px;">Product</th>
                                <th>SKU</th>
                                <th style="width:120px;">Cost</th>
                                <th style="width:130px;">Qty</th>
                                <th style="width:140px;">Subtotal</th>
                                <th style="width:50px;"></th>
                            </tr>
                        </thead>
                        <tbody>`;
        
        let supplierSubtotal = 0;
        group.items.forEach((item, index) => {
            const subtotal = item.qty * item.price;
            supplierSubtotal += subtotal;
            html += `
                <tr class="cart-item">
                    <td class="align-middle">${index + 1}</td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center">
                            <img src="${item.image || '{{ asset("images/no-image.png") }}'}"
                                class="img-fluid rounded mr-2" style="width:40px;">
                            <div>
                                <strong>${item.product_name}</strong>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle text-muted">${item.sku}</td>
                    <td class="align-middle">$${Number(item.price).toLocaleString()}</td>
                    <td class="align-middle">
                        <input type="number" class="form-control form-control-sm item-qty" 
                            value="${item.qty}" min="1" 
                            onchange="updateQty('${item.id}', this.value)">
                    </td>
                    <td class="align-middle font-weight-bold">$${subtotal.toLocaleString()}</td>
                    <td class="align-middle text-center">
                        <a href="javascript:void(0)" class="text-danger" onclick="removeFromCart('${item.id}')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>`;
        });

        html += `
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-end">
                        <div class="text-right">
                            <small class="text-muted">Supplier Subtotal</small><br>
                            <span class="h6 text-primary mb-0">$${supplierSubtotal.toLocaleString()}</span>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    html += `
        <div class="row p-3 bg-white border-top m-0">
            <div class="col-md-8">
                <p class="text-muted small mb-0">
                    * Items from different suppliers will result in separate Purchase Orders being generated.
                </p>
            </div>
            <div class="col-md-4">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Qty:</span>
                    <span class="font-weight-bold">${data.total_qty}</span>
                </div>
                <div class="d-flex justify-content-between border-top pt-2">
                    <span class="h5">Est. Total:</span>
                    <span class="h5 text-primary">$${data.grand_total.toLocaleString()}</span>
                </div>
            </div>
        </div>`;

    cartContent.innerHTML = html;
}

function updateCartBadge(count) {
    const badge = document.querySelector('.btn-warning.position-relative .badge-danger');
    if (badge) {
        badge.textContent = count;
    }
}

async function updateQty(key, qty) {
    try {
        await fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ key, qty })
        });
        loadCart();
    } catch (err) {
        console.error('Error updating qty:', err);
    }
}

async function removeFromCart(key) {
    if (!confirm('Remove this item?')) return;
    try {
        await fetch(`/cart/remove/${key}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        loadCart();
    } catch (err) {
        console.error('Error removing item:', err);
    }
}

function openCartAfterAdded() {
    $('#addToCartModal').modal('hide');
    $('#cartModal').modal('show');
    loadCart();
}

// Initial load
document.addEventListener('DOMContentLoaded', loadCart);

// Show cart modal event
$('#cartModal').on('show.bs.modal', function () {
    loadCart();
});
</script>
