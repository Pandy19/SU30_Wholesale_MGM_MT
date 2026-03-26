@extends('backend.layouts.master')
@section('title', 'Sales Order | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
    <section class="content">

        <!-- PAGE TITLE -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 font-weight-bold text-dark">Sales Order</h1>
                        <p class="text-muted mb-0">
                            Create new sales for B2B and B2C customers
                        </p>
                    </div>
                    <div class="col-sm-6 text-right pt-2">
                        <a href="{{ route('sales_order_history.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                            <i class="fas fa-history mr-1"></i> View Order History
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 1: CUSTOMER SELECTION -->
        <div class="card mb-4 border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
            <div class="card-header bg-white border-bottom-0 pt-3">
                <h5 class="mb-0 font-weight-bold text-primary">
                    <span class="badge badge-primary mr-2">1</span> Customer Selection
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <label class="small text-uppercase font-weight-bold text-muted">Choose Customer</label>
                        <select class="form-control custom-select shadow-sm" id="customer_select">
                            <option value="">-- Search & Choose Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" 
                                        data-type="{{ $customer->type }}" 
                                        data-limit="{{ $customer->credit_limit }}"
                                        data-email="{{ $customer->email }}"
                                        data-phone="{{ $customer->phone }}">
                                    {{ $customer->name }} ({{ $customer->type }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-uppercase font-weight-bold text-muted">Customer Type</label>
                        <input type="text" id="customer_type_display" class="form-control bg-light border-0 shadow-none" value="—" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-uppercase font-weight-bold text-muted">Credit Information</label>
                        <input type="text" id="payment_rule_display" class="form-control bg-light border-0 shadow-none" value="Please select a customer first" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 2: ADD PRODUCT TRIGGER (Hidden by default) -->
        <div id="add_product_section" style="display: none;">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-gradient-primary text-center py-4 text-white product-trigger-card" 
                         id="add_items_btn" style="cursor: pointer; border-radius: 15px;">
                        <div class="card-body">
                            <h3 class="mb-0 font-weight-bold"><i class="fas fa-plus-circle mr-2"></i> Browse & Add Products</h3>
                            <p class="mb-0 mt-2 opacity-75">Click here to open the product catalog</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: CART / ORDER SUMMARY -->
            <div class="card mb-4 border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-list-ul mr-2 text-warning"></i> Current Order Items</h5>
                    <span class="badge badge-warning px-3 py-2" id="cart_count">0 Items</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-uppercase small font-weight-bold">
                                <tr>
                                    <th width="100" class="text-center px-3">Image</th>
                                    <th>Product Details</th>
                                    <th width="120" class="text-center">Quantity</th>
                                    <th width="150" class="text-right">Unit Price</th>
                                    <th width="150" class="text-right pr-4">Subtotal</th>
                                    <th width="80" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="cart_body">
                                <tr id="empty_cart_msg">
                                    <td colspan="6" class="text-center py-5 text-muted bg-white">
                                        <div class="py-4">
                                            <i class="fas fa-shopping-basket fa-4x d-block mb-3 opacity-25"></i>
                                            <h5 class="font-weight-light">Your order is empty.</h5>
                                            <p class="small">Click the blue button above to add products.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- STEP 4: PAYMENT & TOTAL -->
            <div class="row">
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: #fff;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-credit-card mr-2 text-primary"></i> Payment & Shipping
                            </h5>
                            <div class="payment-icons opacity-50">
                                <i class="fab fa-cc-visa fa-2x mr-1"></i>
                                <i class="fab fa-cc-mastercard fa-2x mr-1"></i>
                                <i class="fab fa-cc-paypal fa-2x"></i>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-4">
                            
                            <!-- Section 1: Contact -->
                            <div class="mb-4">
                                <h6 class="text-uppercase small font-weight-bold text-primary mb-3" style="letter-spacing: 1px;">
                                    <i class="fas fa-user-tag mr-1"></i> Customer Contact Info
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold text-muted">Contact Email</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text border-0 bg-light"><i class="fas fa-envelope text-muted"></i></span></div>
                                                <input type="email" class="form-control border-0 bg-light" id="customer_email" placeholder="customer@email.com" style="border-radius: 0 8px 8px 0;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold text-muted">Contact Phone</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text border-0 bg-light"><i class="fas fa-phone text-muted"></i></span></div>
                                                <input type="text" class="form-control border-0 bg-light" id="customer_phone" placeholder="012 345 678" style="border-radius: 0 8px 8px 0;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4 opacity-50">

                            <!-- Section 2: Address & Payment Info -->
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                     <h6 class="text-uppercase small font-weight-bold text-primary mb-3" style="letter-spacing: 1px;">
                                        <i class="fas fa-truck mr-1"></i> Shipping Address
                                    </h6>
                                    <textarea class="form-control border-0 bg-light shadow-none" id="shipping_address" rows="5" 
                                        placeholder="Enter full delivery address..."
                                        style="border-radius: 12px; resize: none;"></textarea>
                                </div>
                                
                                <div class="col-md-6 mb-4">
                                     <h6 class="text-uppercase small font-weight-bold text-primary mb-3" style="letter-spacing: 1px;">
                                        <i class="fas fa-file-invoice-dollar mr-1"></i> Payment Terms
                                    </h6>
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold text-muted">Payment Method</label>
                                        <select class="form-control border-0 bg-light shadow-none" id="pay_method" style="border-radius: 10px; height: 45px;">
                                            <option value="cash">Cash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="card">Credit/Debit Card</option>
                                            <option value="digital_wallet">Digital Wallet (ABA/KHQR)</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="small font-weight-bold text-muted">Payment Status</label>
                                        <select class="form-control border-0 bg-light shadow-none" id="pay_status" style="border-radius: 10px; height: 45px;">
                                            <option value="unpaid">Unpaid / Credit</option>
                                            <option value="partial">Partial Payment</option>
                                            <option value="paid">Fully Paid</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Dynamic Pricing & Notes -->
                            <div class="row mb-3">
                                <div class="col-md-6" id="paid_amount_col" style="display: none;">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted"><i class="fas fa-money-bill-wave mr-1 text-success"></i> Amount Paid ($)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="number" step="0.01" class="form-control form-control-lg font-weight-bold text-success" id="paid_amount" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="due_date_col">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted d-flex justify-content-between">
                                            <span><i class="fas fa-calendar-alt mr-1 text-info"></i> Due Date</span>
                                            <span id="due_date_countdown" class="badge badge-info font-weight-normal px-2">30 days from now</span>
                                        </label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="fas fa-history"></i></span>
                                            </div>
                                            <input type="date" class="form-control form-control-lg" id="due_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <button type="button" class="btn btn-xs btn-outline-secondary mr-1 mb-1 set-due-date" data-days="0">Today</button>
                                            <button type="button" class="btn btn-xs btn-outline-info mr-1 mb-1 set-due-date" data-days="7">Net 7</button>
                                            <button type="button" class="btn btn-xs btn-outline-info mr-1 mb-1 set-due-date" data-days="15">Net 15</button>
                                            <button type="button" class="btn btn-xs btn-outline-primary mr-1 mb-1 set-due-date" data-days="30">Net 30</button>
                                            <button type="button" class="btn btn-xs btn-outline-primary mb-1 set-due-date" data-days="60">Net 60</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="small font-weight-bold text-muted">Order Notes / References</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-sticky-note text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-0 bg-light shadow-none" id="payment_note" 
                                           placeholder="Transaction ID, specific delivery notes, or references..."
                                           style="border-radius: 0 10px 10px 0; height: 45px;">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card border-0 shadow-sm overflow-hidden h-100" style="border-radius: 15px; background: #2c3e50; color: white;">
                        <div class="card-body p-0">
                            <div class="p-4 bg-dark text-white text-center">
                                <h6 class="text-uppercase small mb-1 opacity-75">Order Summary</h6>
                                <h3 class="font-weight-bold mb-0" id="summary_total_header">$0.00</h3>
                            </div>
                            <table class="table table-dark mb-0" style="background: transparent;">
                                <tr>
                                    <th class="px-4 py-4 border-0 font-weight-normal opacity-75" style="width:50%">Subtotal</th>
                                    <td class="text-right px-4 py-4 border-0 h5 mb-0" id="summary_subtotal">$0.00</td>
                                </tr>
                                <tr>
                                    <th class="px-4 py-2 border-0 opacity-75 font-weight-normal">VAT (0%)</th>
                                    <td class="text-right px-4 py-2 border-0">$0.00</td>
                                </tr>
                                <tr>
                                    <th class="px-4 py-2 border-0 opacity-75 font-weight-normal">Discount (<span id="summary_discount_percent">0</span>%)</th>
                                    <td class="text-right px-4 py-2 border-0 text-success" id="summary_discount">-$0.00</td>
                                </tr>
                                <tr style="background: rgba(255,255,255,0.05);">
                                    <th class="px-4 py-5 border-0 h4 font-weight-bold">Grand Total</th>
                                    <td class="text-right px-4 py-5 border-0 h2 font-weight-bold text-warning" id="summary_total">$0.00</td>
                                </tr>
                            </table>
                            <div class="p-4 text-center">
                                <p class="small mb-0 opacity-50">Please review items and customer details before finalizing the sale.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FINAL ACTIONS -->
            <div class="mt-4 pb-5 text-right">
                <button class="btn btn-lg btn-outline-danger border-0 px-4 mr-2" id="clear_cart_btn">
                    <i class="fas fa-trash-alt mr-1"></i> Clear Order
                </button>
                <button class="btn btn-lg btn-success px-5 shadow-lg font-weight-bold" id="confirm_sale_btn" disabled 
                        style="border-radius: 30px;">
                    <i class="fas fa-check-circle mr-2"></i> CONFIRM & FINALIZE SALE
                </button>
            </div>
        </div>

    </section>
</div>

<!-- ================= PRODUCT SELECTOR MODAL ================= -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white py-3 border-0">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-search-plus mr-2"></i> Product Catalog</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light px-4 py-4">
                
                <!-- Filters in Modal -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group shadow-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-0"><i class="fas fa-search text-primary"></i></span>
                            </div>
                            <input type="text" id="prod_search" class="form-control border-0" placeholder="Search name or SKU...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control shadow-sm border-0" id="cat_filter">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control shadow-sm border-0" id="brand_filter">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 text-right">
                        <span class="badge badge-pill badge-primary p-2 mt-1" id="results_count">{{ count($products) }} Items Found</span>
                    </div>
                </div>

                <!-- Product Cards Grid -->
                <div class="row overflow-auto pr-2" id="product_grid" style="max-height: 60vh;">
                    @foreach($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 product-card-col" 
                         data-cat="{{ $product->category_id }}" 
                         data-brand="{{ $product->brand_id }}"
                         data-name="{{ strtolower($product->name) }}"
                         data-sku="{{ strtolower($product->sku) }}">
                        
                        <div class="card h-100 border-0 shadow-sm product-card transition-hover" style="border-radius: 12px;">
                            <div class="position-relative">
                                <div class="bg-white rounded-top d-flex align-items-center justify-content-center" style="height: 160px; overflow: hidden;">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/dist/img/MMOLOGO1.png') }}" 
                                         class="img-fluid" style="max-height: 100%; object-fit: contain;"
                                         onerror="this.src='{{ asset('assets/dist/img/MMOLOGO1.png') }}'">
                                </div>
                                <span class="badge badge-pill badge-{{ $product->total_stock <= 5 ? 'danger' : 'success' }} position-absolute" 
                                      style="top: 10px; right: 10px; font-size: 10px;">
                                    STOCK: {{ $product->total_stock }}
                                </span>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted extra-small font-weight-bold text-uppercase">{{ $product->brand->name }}</span>
                                    <span class="text-muted extra-small font-weight-bold text-uppercase">{{ $product->category->name }}</span>
                                </div>
                                <h6 class="font-weight-bold text-dark mt-1 line-clamp-2" style="height: 40px;">{{ $product->name }}</h6>
                                <p class="small text-muted mb-3">SKU: {{ $product->sku }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="text-primary font-weight-bold mb-0 modal-price-display"
                                        data-retail="{{ $product->display_price }}" 
                                        data-wholesale="{{ $product->b2b_price }}">
                                        ${{ number_format($product->display_price, 2) }}
                                    </h5>
                                    <input type="number" class="form-control form-control-sm text-center qty-modal-input border-light bg-light" 
                                           value="1" min="1" max="{{ $product->total_stock }}" style="width: 50px; border-radius: 8px;">
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 p-3">
                                <button class="btn btn-primary btn-block btn-add-modal shadow-sm font-weight-bold"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-sku="{{ $product->sku }}"
                                        data-image="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/dist/img/MMOLOGO1.png') }}"
                                        data-retail="{{ $product->display_price }}" 
                                        data-wholesale="{{ $product->b2b_price }}"
                                        data-max-cost="{{ $product->max_unit_cost }}"
                                        {{ $product->total_stock <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-cart-plus mr-1"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
            <div class="modal-footer bg-white border-0 py-3">
                <button type="button" class="btn btn-secondary px-5 font-weight-bold shadow-sm" data-dismiss="modal" style="border-radius: 30px;">CLOSE & REVIEW</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-gradient-primary { background: linear-gradient(45deg, #007bff, #00c6ff); }
    .product-trigger-card { transition: all 0.3s; }
    .product-trigger-card:hover { transform: scale(1.01); filter: brightness(1.1); box-shadow: 0 10px 20px rgba(0,123,255,0.2) !important; }
    .transition-hover { transition: transform 0.2s, box-shadow 0.2s; }
    .transition-hover:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .extra-small { font-size: 10px; }
    #product_grid::-webkit-scrollbar { width: 6px; }
    #product_grid::-webkit-scrollbar-thumb { background-color: #ddd; border-radius: 10px; }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let cart = [];
    let customerType = 'B2C';

    // 1. Customer Selection
    $('#customer_select').change(function() {
        const selected = $(this).find(':selected');
        const section = $('#add_product_section');
        
        if (selected.val()) {
            customerType = selected.data('type');
            $('#customer_type_display').val(customerType === 'B2B' ? 'Wholesale (B2B)' : 'Retail (B2C)');
            
            // Set extra info
            $('#customer_email').val(selected.data('email') || '');
            $('#customer_phone').val(selected.data('phone') || '');
            
            if(customerType === 'B2B') {
                $('#payment_rule_display').val('Credit Allowed | Limit: $' + parseFloat(selected.data('limit')).toLocaleString());
            } else {
                $('#payment_rule_display').val('Full Payment Required');
            }
            
            // Show the next steps with animation
            section.fadeIn(400);
            updatePriceDisplay();
            updateCartUI(); 
        } else {
            $('#customer_type_display').val('—');
            $('#payment_rule_display').val('Please select a customer first');
            section.fadeOut(200);
        }
        checkOrderReady();
    });

    // 2. Open Modal
    $('#add_items_btn').click(function() {
        $('#productModal').modal('show');
    });

    function updatePriceDisplay() {
        $('.modal-price-display').each(function() {
            const retail = $(this).data('retail');
            const wholesale = $(this).data('wholesale');
            const price = customerType === 'B2B' ? wholesale : retail;
            $(this).text('$' + parseFloat(price).toLocaleString(undefined, {minimumFractionDigits: 2}));
        });
    }

    // 3. Search & Filter in Modal
    $('#prod_search, #cat_filter, #brand_filter').on('input change', function() {
        const search = $('#prod_search').val().toLowerCase();
        const cat = $('#cat_filter').val();
        const brand = $('#brand_filter').val();
        let visibleCount = 0;

        $('.product-card-col').each(function() {
            const col = $(this);
            const matchesSearch = col.data('name').includes(search) || col.data('sku').includes(search);
            const matchesCat = !cat || col.data('cat') == cat;
            const matchesBrand = !brand || col.data('brand') == brand;

            if (matchesSearch && matchesCat && matchesBrand) {
                col.show();
                visibleCount++;
            } else {
                col.hide();
            }
        });
        $('#results_count').text(visibleCount + ' Items Found');
    });

    // 4. Add from Modal
    $('.btn-add-modal').click(function() {
        const btn = $(this);
        const card = btn.closest('.product-card');
        const id = btn.data('id');
        const qty = parseInt(card.find('.qty-modal-input').val());
        const maxStock = parseInt(card.find('.qty-modal-input').attr('max'));
        
        if (isNaN(qty) || qty < 1) return;

        const retailPrice = btn.data('retail');
        const wholesalePrice = btn.data('wholesale');
        const img = btn.data('image');

        const existingIdx = cart.findIndex(i => i.product_id === id);
        let totalQtyInCart = qty;
        
        if (existingIdx > -1) {
            totalQtyInCart += cart[existingIdx].quantity;
        }

        // --- STOCK CHECK ---
        if (totalQtyInCart > maxStock) {
            const availableToAdd = maxStock - (existingIdx > -1 ? cart[existingIdx].quantity : 0);
            Swal.fire({
                icon: 'warning',
                title: 'Insufficient Stock',
                text: `You already have ${existingIdx > -1 ? cart[existingIdx].quantity : 0} in cart. Only ${availableToAdd} more can be added.`,
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        if (existingIdx > -1) {
            cart[existingIdx].quantity += qty;
        } else {
            cart.push({
                product_id: id,
                name: btn.data('name'),
                sku: btn.data('sku'),
                image: img,
                quantity: qty,
                retail_price: retailPrice,
                wholesale_price: wholesalePrice,
                max_stock: maxStock,
                max_cost: btn.data('max-cost')
            });
        }

        updateCartUI();
        
        // Toast notification
        toastr.success(qty + ' x ' + btn.data('name') + ' added');
        
        // Visual feedback
        btn.html('<i class="fas fa-check"></i> Added').removeClass('btn-primary').addClass('btn-success disabled');
        setTimeout(() => {
            btn.html('<i class="fas fa-cart-plus mr-1"></i> Add').removeClass('btn-success disabled').addClass('btn-primary');
        }, 800);
    });

    function updateCartUI() {
        const body = $('#cart_body');
        body.find('tr:not(#empty_cart_msg)').remove();

        if (cart.length === 0) {
            $('#empty_cart_msg').show();
            $('#cart_count').text('0 Items');
        } else {
            $('#empty_cart_msg').hide();
            $('#cart_count').text(cart.length + ' Items');

            cart.forEach((item, index) => {
                let basePrice = customerType === 'B2B' ? item.wholesale_price : item.retail_price;
                let discountPercent = 0;
                let discountBadge = '';

                if (customerType === 'B2B') {
                    if (item.max_cost < 1000) {
                        if (item.quantity >= 100) discountPercent = 5;
                        else if (item.quantity >= 50) discountPercent = 2;
                    } else { // >= 1000
                        if (item.quantity >= 100) discountPercent = 9;
                        else if (item.quantity >= 50) discountPercent = 4;
                    }
                }

                if (discountPercent > 0) {
                    discountBadge = `<span class="badge badge-success ml-1">-${discountPercent}% Bulk</span>`;
                }

                const priceAfterDiscount = basePrice * (1 - (discountPercent / 100));
                const subtotal = priceAfterDiscount * item.quantity;
                
                body.append(`
                    <tr>
                        <td class="text-center align-middle px-3">
                            <img src="${item.image}" class="rounded shadow-sm border" width="50" height="50" style="object-fit: cover;">
                        </td>
                        <td class="align-middle">
                            <div class="font-weight-bold text-dark">${item.name}</div>
                            <div class="text-muted extra-small">SKU: ${item.sku} | Unit: $${parseFloat(priceAfterDiscount).toFixed(2)} ${discountBadge}</div>
                        </td>
                        <td class="text-center align-middle">
                            <div class="d-flex align-items-center justify-content-center">
                                <button class="btn btn-xs btn-light shadow-none border qty-change" data-index="${index}" data-change="-1"><i class="fas fa-minus text-muted"></i></button>
                                <input type="number" class="form-control form-control-sm text-center mx-2 cart-qty-input" 
                                       data-index="${index}" 
                                       value="${item.quantity}" 
                                       min="1" 
                                       max="${item.max_stock}" 
                                       style="width: 60px; border-radius: 5px;">
                                <button class="btn btn-xs btn-light shadow-none border qty-change" data-index="${index}" data-change="1"><i class="fas fa-plus text-muted"></i></button>
                            </div>
                        </td>
                        <td class="text-right align-middle font-weight-bold text-muted">$${parseFloat(priceAfterDiscount).toFixed(2)}</td>
                        <td class="text-right align-middle pr-4">
                            <span class="h6 font-weight-bold text-primary mb-0">$${subtotal.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                        </td>
                        <td class="text-center align-middle">
                            <button class="btn btn-sm btn-outline-danger border-0 remove-item" data-index="${index}"><i class="fas fa-times-circle"></i></button>
                        </td>
                    </tr>
                `);
            });
        }

        updateCartSummary();
        checkOrderReady();
    }

    $(document).on('click', '.qty-change', function() {
        const idx = $(this).data('index');
        const change = $(this).data('change');
        const item = cart[idx];

        if (change > 0 && item.quantity >= item.max_stock) {
            toastr.error('Max stock reached for ' + item.name);
            return;
        }

        cart[idx].quantity += change;
        if (cart[idx].quantity < 1) cart.splice(idx, 1);
        updateCartUI();
    });

    $(document).on('change keyup', '.cart-qty-input', function() {
        const idx = $(this).data('index');
        let val = parseInt($(this).val()) || 0;
        const item = cart[idx];

        if (val > item.max_stock) {
            toastr.error('Only ' + item.max_stock + ' available for ' + item.name);
            val = item.max_stock;
            $(this).val(val);
        }
        
        if (val < 1) {
            // Option: confirm removal? For now just keep 1 or remove if set to 0
            val = 1;
            $(this).val(val);
        }

        cart[idx].quantity = val;
        
        // Re-calculate discount for this item to show in the subtotal
        let basePrice = customerType === 'B2B' ? item.wholesale_price : item.retail_price;
        let discountPercent = 0;
        if (customerType === 'B2B') {
            if (item.max_cost < 1000) {
                if (val >= 100) discountPercent = 5;
                else if (val >= 50) discountPercent = 2;
            } else {
                if (val >= 100) discountPercent = 9;
                else if (val >= 50) discountPercent = 4;
            }
        }
        
        const priceAfterDiscount = basePrice * (1 - (discountPercent / 100));
        const subtotal = priceAfterDiscount * val;
        
        // Update subtotal in the row
        $(this).closest('tr').find('.h6.font-weight-bold.text-primary').text('$' + subtotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
        
        // Update unit price display with badge if needed
        let discountBadge = '';
        if (discountPercent > 0) {
            discountBadge = `<span class="badge badge-success ml-1">-${discountPercent}% Bulk</span>`;
        }
        $(this).closest('tr').find('.text-muted.extra-small').html(`SKU: ${item.sku} | Unit: $${parseFloat(priceAfterDiscount).toFixed(2)} ${discountBadge}`);

        updateCartSummary(); 
    });

    $(document).on('click', '.remove-item', function() {
        const idx = $(this).data('index');
        cart.splice(idx, 1);
        updateCartUI();
    });

    function updateCartSummary() {
        let subtotal = 0;
        let totalDiscount = 0;
        
        cart.forEach(item => {
            let basePrice = customerType === 'B2B' ? item.wholesale_price : item.retail_price;
            let discountPercent = 0;

            if (customerType === 'B2B') {
                if (item.max_cost < 1000) {
                    if (item.quantity >= 100) discountPercent = 5;
                    else if (item.quantity >= 50) discountPercent = 2;
                } else {
                    if (item.quantity >= 100) discountPercent = 9;
                    else if (item.quantity >= 50) discountPercent = 4;
                }
            }

            const lineSubtotal = basePrice * item.quantity;
            const lineDiscount = lineSubtotal * (discountPercent / 100);
            
            subtotal += lineSubtotal;
            totalDiscount += lineDiscount;
        });

        const grandTotal = subtotal - totalDiscount;
        const avgDiscountPercent = subtotal > 0 ? ((totalDiscount / subtotal) * 100).toFixed(1) : 0;

        $('#summary_subtotal').text('$' + subtotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
        $('#summary_total').text('$' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
        $('#summary_total_header').text('$' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
        $('#summary_discount_percent').text(avgDiscountPercent);
        
        // Show discount line in summary
        if (totalDiscount > 0) {
            $('#summary_discount').text('-$' + totalDiscount.toLocaleString(undefined, {minimumFractionDigits: 2})).closest('tr').show();
        } else {
            $('#summary_discount').closest('tr').hide();
        }
    }

    function checkOrderReady() {
        const hasCustomer = $('#customer_select').val() !== '';
        const hasItems = cart.length > 0;
        $('#confirm_sale_btn').prop('disabled', !(hasCustomer && hasItems));
    }

    // 5. Submit Order
    $('#confirm_sale_btn').click(function() {
        const selected = $('#customer_select').find(':selected');
        const customerId = $('#customer_select').val();
        const payMethod = $('#pay_method').val();
        const payStatus = $('#pay_status').val();
        const paidAmount = $('#paid_amount').val();
        const dueDate = $('#due_date').val();
        const note = $('#payment_note').val();
        
        // --- CREDIT LIMIT CHECK ---
        const total = parseFloat($('#summary_total').text().replace('$', '').replace(',', ''));
        const limit = parseFloat(selected.data('limit'));
        
        if (customerType === 'B2B' && total > limit) {
            Swal.fire({
                icon: 'error',
                title: 'Credit Limit Exceeded',
                text: `Order total ($${total.toLocaleString()}) exceeds customer's credit limit ($${limit.toLocaleString()}).`,
                confirmButtonColor: '#d33'
            });
            return;
        }

        if (!customerId || cart.length === 0) return;

        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> PROCESSING...');

        $.ajax({
            url: "{{ route('sales_order.store') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                customer_id: customerId,
                customer_email: $('#customer_email').val(),
                customer_phone: $('#customer_phone').val(),
                shipping_address: $('#shipping_address').val(),
                items: cart,
                payment_method: payMethod,
                payment_status: payStatus,
                paid_amount: paidAmount,
                due_date: dueDate,
                payment_note: note
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Successful!',
                        text: 'Generating your invoice now...',
                        timer: 1500,
                        showConfirmButton: false,
                        position: 'center'
                    }).then(() => {
                        window.location.href = "{{ url('/sales_order/confirm') }}/" + response.order_id;
                    });
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Something went wrong';
                Swal.fire('Error', msg, 'error');
                $('#confirm_sale_btn').prop('disabled', false).html('<i class="fas fa-check-circle mr-2"></i> CONFIRM & FINALIZE SALE');
            }
        });
    });

    // 6. Payment Status Toggle
    $('#pay_status').change(function() {
        const status = $(this).val();
        if (status === 'partial') {
            $('#paid_amount_col').fadeIn();
            $('#due_date_col').fadeIn();
        } else if (status === 'unpaid') {
            $('#paid_amount_col').hide();
            $('#due_date_col').fadeIn();
            $('#paid_amount').val(0);
        } else {
            $('#paid_amount_col').hide();
            $('#due_date_col').hide();
            $('#paid_amount').val($('#summary_total').text().replace('$', '').replace(',', ''));
        }
    });

    // 7. Due Date Quick Select & Countdown
    $('.set-due-date').click(function() {
        const days = parseInt($(this).data('days'));
        const targetDate = new Date();
        targetDate.setDate(targetDate.getDate() + days);
        
        const year = targetDate.getFullYear();
        const month = String(targetDate.getMonth() + 1).padStart(2, '0');
        const day = String(targetDate.getDate()).padStart(2, '0');
        
        $('#due_date').val(`${year}-${month}-${day}`).trigger('change');
    });

    $('#due_date').on('change', function() {
        const selected = new Date($(this).val());
        const today = new Date();
        today.setHours(0,0,0,0);
        
        const diffTime = selected - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        const badge = $('#due_date_countdown');
        if (diffDays < 0) {
            badge.text('Overdue').removeClass('badge-info badge-success badge-secondary').addClass('badge-danger');
        } else if (diffDays === 0) {
            badge.text('Due Today').removeClass('badge-info badge-danger badge-secondary').addClass('badge-success');
        } else {
            badge.text(`${diffDays} days from now`).removeClass('badge-danger badge-success badge-secondary').addClass('badge-info');
        }
    });

    $('#clear_cart_btn').click(function() {
        if (cart.length > 0 && confirm('Discard this entire order?')) {
            cart = [];
            updateCartUI();
        }
    });
});
</script>
@endpush

@endsection
