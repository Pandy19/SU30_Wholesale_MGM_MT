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
                <button class="btn btn-primary" data-toggle="modal" data-target="#uploadModal" onclick="openCreateModal()">
                    <i class="fas fa-upload"></i> Upload Product
                </button>
            </div>

            {{-- SEARCH & FILTER --}}
            <div class="row mt-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search product...">
                </div>
                <div class="col-md-3">
                    <select class="form-control">
                        <option>All Status</option>
                        <option>Available</option>
                        <option>Unavailable</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row mt-3">

                {{-- PRODUCT CARD --}}
                @for ($i = 1; $i <= 10; $i++)
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">

                    <div class="card h-100 shadow-sm">

                        {{-- CLICKABLE AREA (DETAIL MODAL) --}}
                        <div class="product-click"
                             style="cursor:pointer;"
                             onclick="openDetailModal(this)"
                             data-image="https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png?v=cc5784d1"
                             data-title="iPhone 15 Pro"
                             data-brand="Apple"
                             data-category="Mobile Phone"
                             data-status="Available"
                             data-supplier="ABC Trading"
                             data-cost="1050"
                             data-qty="25">

                            <img
                                src="https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png?v=cc5784d1"
                                class="card-img-top p-2"
                                style="height:150px; object-fit:contain;"
                                alt="product"
                            >

                            <div class="card-body p-2">
                                <h6 class="font-weight-bold mb-1">iPhone 15 Pro</h6>
                                <small class="text-muted">Apple · Mobile Phone</small>

                                <span class="badge badge-success d-block my-1">Available</span>

                                <small>
                                    Supplier: ABC Trading<br>
                                    Cost: $1,050<br>
                                    Qty: 25
                                </small>
                            </div>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="card-footer p-1 text-center">
                            <button class="btn btn-warning btn-xs"
                                    onclick="event.stopPropagation(); openEditModal(this);"
                                    data-image="https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png?v=cc5784d1"
                                    data-title="iPhone 15 Pro"
                                    data-desc="A17 Pro, 256GB storage..."
                                    data-brand="Apple"
                                    data-category="Mobile Phone"
                                    data-status="Available"
                                    data-cost="1050"
                                    data-qty="25">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-danger btn-xs"
                                    onclick="event.stopPropagation(); openDeleteModal('iPhone 15 Pro');">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                    </div>
                </div>
                @endfor

            </div>

            {{-- PAGINATION (RIGHT SIDE) --}}
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item disabled">
                        <a class="page-link">«</a>
                    </li>
                    <li class="page-item active">
                        <a class="page-link">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link">»</a>
                    </li>
                </ul>
    </div>

        </div>
    </section>
</div>

{{-- ================= PRODUCT DETAIL MODAL ================= --}}
{{-- ================= PRODUCT DETAIL MODAL (IMPROVED UI) ================= --}}
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      {{-- HEADER --}}
      <div class="modal-header bg-white">
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
              <div class="card-body">

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

                <hr>

                <div class="d-flex justify-content-between">
                  <span class="text-muted">Last Updated</span>
                  <span class="text-muted">—</span>
                </div>

              </div>
            </div>

          </div>

          {{-- RIGHT: MAIN DETAILS --}}
          <div class="col-lg-8">

            {{-- TITLE STRIP --}}
            <div class="card shadow-sm mb-3">
              <div class="card-body">
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
              <div class="card-header bg-white font-weight-bold">
                <i class="fas fa-align-left mr-1 text-primary"></i> Description
              </div>
              <div class="card-body">
                <div id="detailShortDesc" class="text-muted">
                  No description provided.
                </div>
              </div>
            </div>

            {{-- SPECIFICATIONS (LONG TEXT) --}}
            <div class="card shadow-sm">
              <div class="card-header bg-white font-weight-bold">
                <i class="fas fa-list-ul mr-1 text-success"></i> Specifications
              </div>
              <div class="card-body p-0">
                <div class="p-3" style="background:#f8f9fa;">
                  <pre class="mb-0"
                       style="white-space:pre-wrap;font-family:inherit;line-height:1.45;"
                       id="detailSpecs">
Chipset
A17 Pro

RAM
8GB

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
                  </pre>
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


{{-- ================= UPLOAD / EDIT MODAL ================= --}}
{{-- ================= UPLOAD / EDIT PRODUCT MODAL (IMPROVED UI) ================= --}}
<div class="modal fade" id="uploadModal">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <form onsubmit="event.preventDefault(); uploadSuccess();">

                {{-- HEADER --}}
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold" id="uploadModalTitle">
                        <i class="fas fa-box-open mr-2 text-primary"></i>
                        Upload Product
                    </h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>

                {{-- BODY --}}
                <div class="modal-body">

                    {{-- SECTION: IMAGE --}}
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-white font-weight-bold">
                            <i class="fas fa-image mr-1 text-info"></i> Product Image
                        </div>

                        <div class="card-body">
                            <div class="row align-items-center">

                                <div class="col-md-3 text-center">
                                    <div id="imgBox"
                                         class="border rounded d-flex align-items-center justify-content-center"
                                         style="width:140px;height:140px;background:#f8f9fa;">
                                        <img id="previewImg"
                                             style="max-width:100%;max-height:100%;object-fit:contain;display:none;">
                                        <span id="imgBoxText" class="text-muted small">
                                            No Image
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <label class="font-weight-bold mb-1">Upload Image</label>
                                    <input type="file"
                                           class="form-control"
                                           accept="image/*"
                                           onchange="previewImage(event)">
                                    <small class="text-muted">
                                        Recommended size: 800×800px (PNG/JPG)
                                    </small>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- SECTION: BASIC INFO --}}
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-white font-weight-bold">
                            <i class="fas fa-info-circle mr-1 text-success"></i>
                            Basic Information
                        </div>

                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <label class="font-weight-bold">Product Name</label>
                                    <input class="form-control" id="form_title" placeholder="Enter product name">
                                </div>

                                <div class="col-md-6">
                                    <label class="font-weight-bold">Status</label>
                                    <select class="form-control" id="form_status">
                                        <option>Available</option>
                                        <option>Unavailable</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="font-weight-bold">Description</label>
                                    <textarea class="form-control"
                                              rows="4"
                                              id="form_desc"
                                              placeholder="Write product description..."></textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- SECTION: CLASSIFICATION --}}
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-white font-weight-bold">
                            <i class="fas fa-tags mr-1 text-warning"></i>
                            Classification
                        </div>

                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <label class="font-weight-bold">Brand</label>
                                    <select class="form-control" id="form_brand">
                                        <option>Apple</option>
                                        <option>Samsung</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="font-weight-bold">Category</label>
                                    <select class="form-control" id="form_category">
                                        <option>Mobile Phone</option>
                                        <option>Smart TV</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- SECTION: PRICING & STOCK --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-white font-weight-bold">
                            <i class="fas fa-coins mr-1 text-danger"></i>
                            Pricing & Stock
                        </div>

                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <label class="font-weight-bold">Cost Price ($)</label>
                                    <input type="number"
                                           class="form-control"
                                           id="form_cost"
                                           placeholder="0.00">
                                </div>

                                <div class="col-md-6">
                                    <label class="font-weight-bold">Quantity</label>
                                    <input type="number"
                                           class="form-control"
                                           id="form_qty"
                                           placeholder="0">
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-success px-4" id="submitBtn">
                        <i class="fas fa-check mr-1"></i>
                        Submit Product
                    </button>
                </div>

            </form>

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
            <p class="text-muted mb-0" id="deleteText">
                You want to delete this product.
            </p>

            <div class="mt-3">
                <button class="btn btn-danger btn-sm mr-2">Yes, Delete</button>
                <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

{{-- ================= SUCCESS MODAL ================= --}}
<div class="modal fade" id="successModal">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <i class="fas fa-check-circle text-success" style="font-size:48px;"></i>
            <h5 class="mt-3">Upload product successful</h5>
        </div>
    </div>
</div>

{{-- ================= SCRIPTS ================= --}}



@endsection
