@extends('backend.layouts.master')
@section('title', 'Good Receiving | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- ===================================================== -->
<!-- PAGE TITLE -->
<!-- ===================================================== -->
<div class="mb-4">
    <h3 class="mb-1">Goods Receiving & Approval</h3>
    <p class="text-muted mb-0">
        Verify purchased products before adding them to stock
    </p>
</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-4">
<div class="card-body">
<div class="row">

    <div class="col-md-3 mb-2">
        <input type="text" class="form-control"
               placeholder="Search Product / SKU / PO No">
    </div>

    <div class="col-md-2 mb-2">
        <select class="form-control">
            <option>All Brands</option>
            <option>Samsung</option>
            <option>Apple</option>
        </select>
    </div>

    <div class="col-md-2 mb-2">
        <select class="form-control">
            <option>All Categories</option>
            <option>Mobile Phone</option>
            <option>Smart TV</option>
        </select>
    </div>

    <div class="col-md-2 mb-2">
        <select class="form-control">
            <option>All Suppliers</option>
            <option>Global Tech Supply</option>
            <option>Asia Mobile Distribution</option>
        </select>
    </div>

    <div class="col-md-3 mb-2">
        <select class="form-control">
            <option>All Status</option>
            <option>Pending</option>
            <option>Accepted</option>
            <option>Rejected</option>
        </select>
    </div>

</div>
</div>
</div>

<!-- ===================================================== -->
<!-- GOODS RECEIVING TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0 align-middle">
<thead class="thead-light">
<tr>
    <th class="text-center">Image</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Brand</th>
    <th>Supplier</th>
    <th class="text-center">Ordered</th>
    <th class="text-center">Received</th>
    <th class="text-center">Accept</th>
    <th class="text-center">Reject</th>
    <th class="text-right">Unit Cost</th>
    <th class="text-right">Total</th>
    <th>Status</th>
    <th>Approved By</th>
    <th>Date</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

<!-- ================= PENDING ================= -->
<tr>
    <td class="text-center">
        <img src="https://www.myg.in/images/thumbnails/300/300/detailed/75/s24ultraviolet1-removebg-preview.png.png"
             class="img-thumbnail"
             width="55">
    </td>
    <td>
        <strong>Samsung Galaxy S24</strong>
    </td>
    <td>SGS24</td>
    <td>Samsung</td>
    <td>Global Tech Supply</td>
    <td class="text-center">10</td>
    <td class="text-center"><strong>10</strong></td>

    <td class="text-center">
        <input type="number"
               class="form-control form-control-sm text-center"
               value="7" min="0" max="10">
    </td>

    <td class="text-center">
        <input type="number"
               class="form-control form-control-sm text-center"
               value="3" min="0" max="10">
    </td>

    <td class="text-right">$950</td>
    <td class="text-right">$9,500</td>

    <td>
        <span class="badge badge-info">Pending</span>
    </td>
    <td class="text-muted">—</td>
    <td class="text-muted">—</td>

    <td class="text-center">
        <div class="btn-group-vertical">
            <button class="btn btn-sm btn-success mb-1">
                Approve
            </button>
            <button class="btn btn-sm btn-danger mb-1"
                    data-toggle="modal"
                    data-target="#rejectReasonModal">
                Reject
            </button>
            <button class="btn btn-sm btn-outline-secondary" disabled>
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </td>
</tr>

<!-- ================= ACCEPTED ================= -->
<tr>
    <td class="text-center">
        <img src="https://images-cdn.ubuy.co.in/668e509932f72820f85b4e0f-samsung-55-class-4k-uhdtv-2160p-hdr.jpg"
             class="img-thumbnail"
             width="55">
    </td>
    <td><strong>Samsung Smart TV 55"</strong></td>
    <td>SS-TV55</td>
    <td>Samsung</td>
    <td>Global Tech Supply</td>
    <td class="text-center">5</td>
    <td class="text-center">5</td>
    <td class="text-center text-muted">5</td>
    <td class="text-center text-muted">0</td>
    <td class="text-right">$700</td>
    <td class="text-right">$3,500</td>
    <td><span class="badge badge-success">Accepted</span></td>
    <td>Admin</td>
    <td>2025-01-15</td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-secondary"
                onclick="printWithStatus('approved','Admin','2025-01-15')">
            <i class="fas fa-print"></i> Print
        </button>
    </td>
</tr>

<!-- ================= REJECTED ================= -->
<tr>
    <td class="text-center">
        <img src="https://store.storeimages.cdn-apple.com/4668/as-images.apple.com/is/iphone-15-pro-blue-titanium-select?wid=470&hei=556"
             class="img-thumbnail"
             width="55">
    </td>
    <td><strong>iPhone 15</strong></td>
    <td>IP15</td>
    <td>Apple</td>
    <td>Asia Mobile Distribution</td>
    <td class="text-center">8</td>
    <td class="text-center">6</td>
    <td class="text-center text-muted">0</td>
    <td class="text-center text-muted">6</td>
    <td class="text-right">$980</td>
    <td class="text-right">$5,880</td>
    <td><span class="badge badge-danger">Rejected</span></td>
    <td>Staff</td>
    <td>2025-01-14</td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-secondary"
                onclick="printWithStatus('rejected','Staff','2025-01-14','Damaged units')">
            <i class="fas fa-print"></i> Print
        </button>
    </td>
</tr>

</tbody>
</table>

</div>

<!-- PAGINATION -->
<div class="card-footer clearfix">
<ul class="pagination pagination-sm m-0 float-right">
    <li class="page-item disabled"><a class="page-link">«</a></li>
    <li class="page-item active"><a class="page-link">1</a></li>
    <li class="page-item"><a class="page-link">2</a></li>
    <li class="page-item"><a class="page-link">3</a></li>
    <li class="page-item"><a class="page-link">»</a></li>
</ul>
</div>

</div>

</section>
</div>

<!-- ===================================================== -->
<!-- REJECT REASON MODAL -->
<!-- ===================================================== -->
<div class="modal fade" id="rejectReasonModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header bg-danger text-white">
    <h5 class="modal-title">
        <i class="fas fa-times-circle mr-1"></i> Reject Product
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
    <div class="form-group">
        <label><strong>Rejection Reason</strong></label>
        <textarea class="form-control" rows="3"
                  placeholder="Explain why this product is rejected"></textarea>
    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-secondary" data-dismiss="modal">
        Cancel
    </button>
    <button class="btn btn-danger">
        Confirm Reject
    </button>
</div>

</div>
</div>
</div>

@endsection
