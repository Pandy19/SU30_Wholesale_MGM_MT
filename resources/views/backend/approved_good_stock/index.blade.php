@extends('backend.layouts.master')
@section('title', 'Approved Goods | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content">

<!-- PAGE TITLE -->

 <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Approved Goods → Stock Approval</h3>
                    <p class="text-muted mb-0">
                        Add approved goods into stock (single warehouse)
                    </p>
                </div>
                
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Stock</a></li>
                        <li class="breadcrumb-item active">Approve Good</li>
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
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-boxes"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Approved Items</span>
                <span class="info-box-number">6</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fas fa-hourglass-half"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Pending Stock Add</span>
                <span class="info-box-number">4</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-secondary">
                <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Added to Stock</span>
                <span class="info-box-number">2</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger">
                <i class="fas fa-exclamation-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">High Value Pending</span>
                <span class="info-box-number">$12,400</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-3">
<div class="card-body">
<div class="row">

    <div class="col-md-3">
        <input type="text" class="form-control"
               placeholder="Search Product / SKU / PO No">
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Brands</option>
            <option>Samsung</option>
            <option>Apple</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Categories</option>
            <option>Mobile Phone</option>
            <option>Smart TV</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Suppliers</option>
            <option>Global Tech Supply</option>
            <option>Asia Mobile Distribution</option>
        </select>
    </div>

    <div class="col-md-3">
        <select class="form-control">
            <option>All Status</option>
            <option>Pending</option>
            <option>Added to Stock</option>
        </select>
    </div>

</div>
</div>
</div>

<!-- ===================================================== -->
<!-- APPROVED GOODS TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>Image</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Brand</th>
    <th>Supplier</th>
    <th>Approved Qty</th>
    <th>Unit Cost</th>
    <th>Total Value</th>
    <th>PO No</th>
    <th>Storage Location</th>
    <th>Approved By</th>
    <th>Approved Date</th>
    <th>Status</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

<!-- ================= PENDING ================= -->
<tr>
    <td class="text-center">
        <img src="https://www.myg.in/images/thumbnails/300/300/detailed/75/s24ultraviolet1-removebg-preview.png.png"
             width="50">
    </td>
    <td>Samsung Galaxy S24</td>
    <td>SGS24</td>
    <td>Samsung</td>
    <td>Global Tech Supply</td>
    <td><strong>7</strong></td>
    <td>$950</td>
    <td>$6,650</td>
    <td>PO-0001</td>

    <!-- STORAGE LOCATION (CATEGORY SHELF ONLY) -->
    <td>
        <select class="form-control form-control-sm">
            <option selected disabled>Select shelf</option>
            <option>Mobile Shelf A1</option>
            <option>Mobile Shelf A2</option>
            <option>Mobile Shelf A3</option>
        </select>
    </td>

    <td>Admin</td>
    <td>2025-01-15</td>
    <td>
        <span class="badge badge-warning">Pending</span>
    </td>
    <td class="text-center">
        <button class="btn btn-sm btn-success"
            onclick="openAddToStockModal({
                name: 'Samsung Galaxy S24',
                sku: 'SGS24',
                po: 'PO-0001',
                supplier: 'Global Tech Supply',
                brand: 'Samsung',
                approvedQty: 7,
                unitCost: 950,
                image: 'https://www.myg.in/images/thumbnails/300/300/detailed/75/s24ultraviolet1-removebg-preview.png.png'
            })">
            Add to Stock
        </button>

    </td>
</tr>

<!-- ================= ADDED ================= -->
<tr>
    <td class="text-center">
        <img src="https://images-cdn.ubuy.co.in/668e509932f72820f85b4e0f-samsung-55-class-4k-uhdtv-2160p-hdr.jpg"
             width="50">
    </td>
    <td>Samsung Smart TV 55"</td>
    <td>SS-TV55</td>
    <td>Samsung</td>
    <td>Global Tech Supply</td>
    <td>5</td>
    <td>$700</td>
    <td>$3,500</td>
    <td>PO-0002</td>

    <!-- READ ONLY LOCATION -->
    <td>TV Shelf T1</td>

    <td>Admin</td>
    <td>2025-01-14</td>
    <td>
        <span class="badge badge-success">Added</span>
    </td>
    <td class="text-center text-muted">—</td>
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
<!-- Add to Stock Modal -->
<div class="modal fade" id="addToStockModal" tabindex="-1" role="dialog" aria-labelledby="addToStockModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="addToStockModalLabel">
          Add Approved Item to Stock
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <!-- Product summary -->
        <div class="media">
          <img id="m_image" src="" class="mr-3 rounded border" style="width:72px;height:72px;object-fit:cover;" alt="Product">
          <div class="media-body">
            <div class="d-flex align-items-start justify-content-between">
              <div>
                <h6 class="mb-1" id="m_name">—</h6>
                <div class="text-muted small">
                  SKU: <span id="m_sku">—</span> · PO: <span id="m_po">—</span>
                </div>
                <div class="text-muted small">
                  Brand: <span id="m_brand">—</span> · Supplier: <span id="m_supplier">—</span>
                </div>
              </div>

              <div class="text-right">
                <div class="small text-muted">Approved Qty</div>
                <div class="h5 mb-0" id="m_approvedQty">0</div>
              </div>
            </div>
          </div>
        </div>

        <hr class="my-3">

        <div class="row">
          <!-- Quantity -->
          <div class="col-md-5">
            <label class="mb-1">Add Quantity</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <button class="btn btn-outline-secondary" type="button" id="qtyMinus">
                  <i class="fas fa-minus"></i>
                </button>
              </div>

              <input type="number" class="form-control text-center" id="m_qty" value="1" min="1">

              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="qtyPlus">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>
            <small class="text-muted">
              Max: <span id="m_qtyMax">0</span>
            </small>
          </div>

          <!-- Storage location -->
          <div class="col-md-7">
            <label class="mb-1">
              Storage Location <span class="text-danger">*</span>
            </label>
            <select class="form-control" id="m_location">
              <option value="" selected disabled>Select shelf / location</option>
              <option>Mobile Shelf A1</option>
              <option>Mobile Shelf A2</option>
              <option>Mobile Shelf A3</option>
              <option>TV Shelf T1</option>
            </select>
            <small class="text-muted">
              Pick a shelf before adding to stock.
            </small>
          </div>
        </div>

        <div class="row mt-3">
          <!-- Notes -->
          <div class="col-md-8">
            <label class="mb-1">Notes (optional)</label>
            <textarea class="form-control" id="m_notes" rows="2" placeholder="E.g., Box condition, serial range, urgent placement..."></textarea>
          </div>

          <!-- Cost summary -->
          <div class="col-md-4">
            <div class="border rounded p-3 bg-light">
              <div class="d-flex justify-content-between small text-muted">
                <span>Unit Cost</span>
                <span id="m_unitCost">$0</span>
              </div>
              <div class="d-flex justify-content-between mt-1">
                <strong>Total</strong>
                <strong id="m_total">$0</strong>
              </div>
              <div class="small text-muted mt-2">
                This will be recorded as stock-in value.
              </div>
            </div>
          </div>
        </div>

        <!-- Inline validation message -->
        <div class="alert alert-warning mt-3 d-none" id="m_warn">
          Please select a storage location and ensure quantity is valid.
        </div>

        <!-- Hidden fields (optional) -->
        <input type="hidden" id="m_payload">
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
          Cancel
        </button>

        <button type="button" class="btn btn-success" id="m_confirmBtn">
          <span class="spinner-border spinner-border-sm mr-2 d-none" id="m_spinner"></span>
          Confirm Add to Stock
        </button>
      </div>

    </div>
  </div>
</div>

@endsection
