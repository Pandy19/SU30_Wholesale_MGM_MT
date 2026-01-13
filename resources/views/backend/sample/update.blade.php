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
                    <select class="form-control" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="mobile phone">Mobile Phone</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="brandFilter">
                        <option value="">All Brands</option>
                        <option value="apple">Apple</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Search product name or SKU">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-warning btn-block position-relative" data-toggle="modal" data-target="#cartModal">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="badge badge-danger navbar-badge" style="position: absolute; top: -5px; right: -5px;">2</span>
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
                            <tr>
                                <td class="text-center">
                                    <img src="https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png?v=cc5784d1" class="img-fluid rounded">
                                </td>
                                <td><strong>iPhone 15 Pro 256GB</strong><br><small class="text-muted">SKU: IP15P-256</small></td>
                                <td>Apple</td>
                                <td>Mobile Phone</td>
                                <td><span class="badge badge-info">2 Suppliers</span></td>
                                <td>$950</td>
                                <td><span class="badge badge-success">Available</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#supplierSelectModal">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- ================= PRODUCT ROW ================= -->
        <tr data-brand="samsung" data-category="mobile phone">
            <td class="text-center">
                <img src="https://www.myg.in/images/thumbnails/300/300/detailed/75/s24ultraviolet1-removebg-preview.png.png"
                     class="img-fluid rounded">
            </td>
            <td>
                <strong>Samsung Galaxy S24</strong><br>
                <small class="text-muted">SKU: SGS24</small>
            </td>
            <td>
                <span class="text-truncate d-inline-block"
                      style="max-width: 220px;"
                      title="6.2” AMOLED display, 256GB storage, Snapdragon processor, 5G enabled, AI camera features, Gorilla Glass, Android latest version">
                    6.2” AMOLED display, 256GB storage, Snapdragon processor, 5G enabled, AI camera features, Gorilla Glass, Android latest version
                </span>
            </td>
            <td>Samsung</td>
            <td>Mobile Phone</td>
            <td>
                <span class="badge badge-info">1 Supplier</span>
            </td>
            <td>$720</td>
            <td>
                <span class="badge badge-warning">Limited</span>
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-success"
                        data-toggle="modal"
                        data-target="#supplierSelectModal">
                    <i class="fas fa-cart-plus"></i>
                </button>
            </td>
        </tr>
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
                                <img src="https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png?v=cc5784d1" class="img-fluid rounded">
                            </div>
                            <div class="col-md-10">
                                <h5 class="mb-1">iPhone 15 Pro 256GB</h5>
                                <small class="text-muted">Brand: Apple | SKU: IP15P-256</small>
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
                            <tbody>
                                <tr>
                                    <td>Global Tech Supply</td>
                                    <td>SUP-001</td>
                                    <td>012 345 678</td>
                                    <td>info@globaltech.com</td>
                                    <td class="font-weight-bold text-primary">$950</td>
                                    <td><span class="badge badge-success">120</span></td>
                                    <td>5 Days</td>
                                    <td><span class="badge badge-success">Available</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning font-weight-bold" onclick="alert('Item added to Cart!')">
                                            <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Asia Mobile Distribution</td>
                                    <td>SUP-002</td>
                                    <td>098 765 432</td>
                                    <td>sales@asiamobile.com</td>
                                    <td class="font-weight-bold text-primary">$980</td>
                                    <td><span class="badge badge-warning">40</span></td>
                                    <td>3 Days</td>
                                    <td><span class="badge badge-success">Available</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning font-weight-bold" onclick="alert('Item added to Cart!')">
                                            <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mb-3">
    <div class="card-header">
        <strong>
            <i class="fas fa-history mr-1"></i> Supplier Price History
        </strong>
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
            <tbody>
                <tr>
                    <td>Global Tech Supply</td>
                    <td>$920</td>
                    <td>2024-11-15</td>
                    <td>2024-11-15</td>
                </tr>
                <tr>
                    <td>Global Tech Supply</td>
                    <td>$950</td>
                    <td>2025-01-10</td>
                    <td>2025-01-10</td>
                </tr>
                <tr>
                    <td>Asia Mobile Distribution</td>
                    <td>$980</td>
                    <td>2025-01-12</td>
                    <td>2025-01-12</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <strong>
            <i class="fas fa-align-left mr-1"></i> Product Specifications
        </strong>
    </div>
    <div class="card-body p-0">
        <pre class="border-0 rounded-0 p-3 bg-light mb-0"
             style="max-height: 400px; overflow-y: auto; font-size: 13px;">
Network    Technology
GSM / CDMA / HSPA / EVDO / LTE / 5G

Launch
Announced  2023, September 12
Status     Available. Released 2023, September 22

Body
Dimensions 146.6 x 70.6 x 8.3 mm
Weight     187 g
Build      Glass front, glass back, titanium frame (grade 5)
SIM        Nano-SIM + eSIM
IP68 dust tight and water resistant (up to 6m for 30 min)

Display
Type       LTPO Super Retina XDR OLED, 120Hz, HDR10, Dolby Vision
Size       6.1 inches
Resolution 1179 x 2556 pixels

Platform
OS         iOS 17 (Upgradeable)
Chipset    Apple A17 Pro (3 nm)
CPU        Hexa-core
GPU        Apple GPU (6-core)

Memory
Internal  128GB / 256GB / 512GB / 1TB
RAM       8GB
NVMe storage

Main Camera
48 MP (Wide) + 12 MP (Telephoto) + 12 MP (Ultra-wide)
LiDAR Scanner
4K HDR Video, ProRes

Selfie Camera
12 MP
4K HDR Video

Sound
Stereo speakers

Connectivity
Wi-Fi 6E
Bluetooth 5.3
USB Type-C 3.2 Gen 2
NFC supported

Battery
Li-Ion 3274 mAh
Fast charging (50% in 30 min)
MagSafe & Qi wireless charging

Other Features
Face ID
Ultra Wideband (UWB)
Emergency SOS via satellite
Apple Pay supported
        </pre>
    </div>
</div>

</div>

<!-- FOOTER -->
<div class="modal-footer">
    <button class="btn btn-secondary" data-dismiss="modal">
        Close
    </button>
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
                
                <div class="table-responsive">
                    <table class="table table-striped mb-0" id="cartTable">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 250px;">Product</th>
                                <th>SKU</th>
                                <th>Description</th>
                                <th>Supplier</th> <th style="width: 150px;">Cost</th>
                                <th style="width: 120px;">Qty</th>
                                <th style="width: 150px;">Subtotal</th>
                                <th style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="cart-item">
                                <td class="align-middle">1</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <img src="https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png?v=cc5784d1" class="img-fluid rounded mr-2" style="width: 40px;">
                                        <strong>iPhone 15 Pro</strong>
                                    </div>
                                </td>
                                <td class="align-middle text-muted">IP15P-256</td>
                                <td class="align-middle small">Mobile Phone - 256GB, Factory Unlocked, Titanium</td>
                                <td class="align-middle">
                                    <span class="badge badge-outline-primary">Global Tech Supply</span>
                                </td>
                                <td class="align-middle">
                                    $<span class="item-price">950.00</span>
                                </td>
                                <td class="align-middle">
                                    <input type="number" class="form-control form-control-sm item-qty" value="1" min="1">
                                    <small class="text-muted">Avail: 120</small>
                                </td>
                                <td class="align-middle font-weight-bold">
                                    $<span class="item-subtotal">950.00</span>
                                </td>
                                <td class="align-middle">
                                    <a href="#" class="text-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>

                            <tr class="cart-item">
                                <td class="align-middle">2</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <img src="https://www.myg.in/images/thumbnails/300/300/detailed/75/s24ultraviolet1-removebg-preview.png.png" class="img-fluid rounded mr-2" style="width: 40px;">
                                        <strong>Samsung S24</strong>
                                    </div>
                                </td>
                                <td class="align-middle text-muted">SGS24</td>
                                <td class="align-middle small">6.2” AMOLED, 256GB, 5G Enabled</td>
                                <td class="align-middle">
                                    <span class="badge badge-outline-secondary">Asia Mobile Dist.</span>
                                </td>
                                <td class="align-middle">
                                    $<span class="item-price">720.00</span>
                                </td>
                                <td class="align-middle">
                                    <input type="number" class="form-control form-control-sm item-qty" value="2" min="1">
                                    <small class="text-muted">Avail: 40</small>
                                </td>
                                <td class="align-middle font-weight-bold">
                                    $<span class="item-subtotal">1440.00</span>
                                </td>
                                <td class="align-middle">
                                    <a href="#" class="text-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row p-3 bg-white border-top">
                    <div class="col-md-8">
                        <p class="text-muted small mb-0">
                            * Items from different suppliers will result in separate Purchase Orders being generated upon confirmation.
                        </p>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Qty:</span>
                            <span id="total-qty" class="font-weight-bold">3</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2">
                            <span class="h5">Est. Total:</span>
                            <span class="h5 text-primary" id="grand-total">$2,390.00</span>
                        </div>
                    </div>
                </div>
                </div>

            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Continue Shopping</button>
                <a href="{{url('/purchase_confirmation')}}" class="btn btn-primary">
                    <i class="fas fa-check-circle mr-1"></i> Proceed to Confirmation
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Simple Script to Handle Cart Calculations inside the Modal
    $(document).ready(function() {
        
        function updateCartTotals() {
            let totalQty = 0;
            let grandTotal = 0;

            $('.cart-item').each(function() {
                // Get Price
                let price = parseFloat($(this).find('.item-price').text().replace(',', ''));
                // Get Qty
                let qty = parseInt($(this).find('.item-qty').val());
                if(qty < 1 || isNaN(qty)) qty = 1;

                // Update Row Subtotal
                let subtotal = price * qty;
                $(this).find('.item-subtotal').text(subtotal.toFixed(2));

                // Add to Grand Totals
                totalQty += qty;
                grandTotal += subtotal;
            });

            // Update UI Totals
            $('#total-qty').text(totalQty);
            $('#grand-total').text('$' + grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        }

        // Trigger update when any quantity input changes
        $(document).on('change keyup', '.item-qty', function() {
            updateCartTotals();
        });

    });
</script>
@endsection