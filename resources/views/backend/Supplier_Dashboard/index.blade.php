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
                <button class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
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
                             data-toggle="modal"
                             data-target="#detailModal"
                             style="cursor:pointer;">

                            <img
                                src="https://assets.swappie.com/cdn-cgi/image/width=600,height=600,fit=contain,format=auto/swappie-iphone-15-pro-natural-titanium.png?v=cc5784d1"
                                class="card-img-top p-2"
                                style="height:150px; object-fit:contain;"
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
                                    onclick="event.stopPropagation();"
                                    data-toggle="modal"
                                    data-target="#uploadModal">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-danger btn-xs"
                                    onclick="event.stopPropagation();"
                                    data-toggle="modal"
                                    data-target="#deleteModal">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                    </div>
                </div>
                @endfor

            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-4">
                <ul class="pagination">
                    <li class="page-item active"><a class="page-link">1</a></li>
                    <li class="page-item"><a class="page-link">2</a></li>
                    <li class="page-item"><a class="page-link">3</a></li>
                </ul>
            </div>

        </div>
    </section>
</div>

{{-- ================= PRODUCT DETAIL MODAL ================= --}}
<div class="modal fade" id="detailModal">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Product Specifications</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <pre style="white-space:pre-wrap; font-family:inherit;">
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

{{-- ================= UPLOAD / EDIT MODAL ================= --}}
<div class="modal fade" id="uploadModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form onsubmit="event.preventDefault(); uploadSuccess();">

                <div class="modal-header">
                    <h5 class="modal-title">Upload / Edit Product</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6">
                            <label>Product Image</label>
                            <input type="file" class="form-control" onchange="previewImage(event)">
                            <img id="previewImg" class="mt-2" style="height:80px; display:none;">
                        </div>

                        <div class="col-md-6">
                            <label>Product Name</label>
                            <input class="form-control">
                        </div>

                        <div class="col-md-12 mt-2">
                            <label>Description</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>

                        <div class="col-md-4 mt-2">
                            <label>Brand</label>
                            <select class="form-control">
                                <option>Apple</option>
                                <option>Samsung</option>
                            </select>
                        </div>

                        <div class="col-md-4 mt-2">
                            <label>Category</label>
                            <select class="form-control">
                                <option>Mobile Phone</option>
                                <option>Smart TV</option>
                            </select>
                        </div>

                        <div class="col-md-4 mt-2">
                            <label>Status</label>
                            <select class="form-control">
                                <option>Available</option>
                                <option>Unavailable</option>
                            </select>
                        </div>

                        <div class="col-md-4 mt-2">
                            <label>Cost Price</label>
                            <input class="form-control">
                        </div>

                        <div class="col-md-4 mt-2">
                            <label>Quantity</label>
                            <input class="form-control">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Submit Product</button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ================= DELETE MODAL ================= --}}
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center p-3">
            <h5>Delete Product?</h5>
            <p class="text-muted">This action cannot be undone.</p>

            <div class="mt-3">
                <button class="btn btn-danger btn-sm">Yes, Delete</button>
                <button class="btn btn-secondary btn-sm" data-dismiss="modal">
                    Cancel
                </button>
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
<script>
function previewImage(e) {
    let img = document.getElementById('previewImg');
    img.src = URL.createObjectURL(e.target.files[0]);
    img.style.display = 'block';
}

function uploadSuccess() {
    $('#uploadModal').modal('hide');
    $('#successModal').modal('show');
}
</script>

@endsection
