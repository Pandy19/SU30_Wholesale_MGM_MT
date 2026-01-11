@extends('backend.layouts.master')
@section('title', 'Suppliers | Wholesale MGM')
@section('main-content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Electronics – Brand & Supplier Overview</h1>
            <p class="text-muted mb-0">
            Click a brand row to view suppliers for each electronic brand.
        </p>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
<div class="container-fluid">

{{-- SEARCH, FILTER & ACTION BUTTONS --}}
<div class="row mb-3 align-items-center">

    <div class="col-md-4">
        <input type="text" id="brandSearch"
               class="form-control"
               placeholder="Search brand or supplier">
    </div>

    <div class="col-md-3">
        <select id="categoryFilter" class="form-control">
            <option value="">All Categories</option>
            <option value="mobile-phone">Mobile Phone</option>
            <option value="smart-tv">Smart TV</option>
            <option value="washing-machine">Washing Machine</option>
            <option value="air-conditioner">Air Conditioner</option>
            <option value="refrigerator">Refrigerator</option>
            <option value="audio-devices">Audio Devices</option>
        </select>
    </div>

    <div class="col-md-5 text-right">
        <button class="btn btn-primary mr-2" data-toggle="modal" data-target="#addBrandModal">
            <i class="fas fa-plus mr-1"></i> Add Brand
        </button>

        <button class="btn btn-success" data-toggle="modal" data-target="#addSupplierModal">
            <i class="fas fa-user-plus mr-1"></i> Add Supplier
        </button>

        <button class="btn btn-info ml-2" data-toggle="modal" data-target="#addCategoryModal">
            <i class="fas fa-tags mr-1"></i> Add Category
        </button>

    </div>

</div>

<!-- ADD BRAND MODAL -->
<div class="modal fade" id="addBrandModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="fas fa-industry mr-1"></i> Add New Brand
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
<form>

    <div class="form-group">
        <label>Brand Name</label>
        <input type="text" class="form-control" placeholder="Enter brand name">
    </div>

    <div class="form-group">
        <label>Category</label>
        <select class="form-control">
            <option>Mobile Phone</option>
            <option>Smart TV</option>
            <option>Washing Machine</option>
            <option>Air Conditioner</option>
            <option>Refrigerator</option>
            <option>Audio Devices</option>
        </select>
    </div>

    <div class="form-group">
        <label>Brand Logo</label>
        <input type="file" class="form-control">
    </div>

    <div class="form-group">
        <label>Status</label>
        <select class="form-control">
            <option>Active</option>
            <option>Inactive</option>
        </select>
    </div>

</form>
</div>

<div class="modal-footer">
    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    <button class="btn btn-primary" onclick="alert('Brand submitted successfully!')">
        Submit Brand
    </button>
</div>

</div>
</div>
</div>


<!-- ADD SUPPLIER MODAL -->
<div class="modal fade" id="addSupplierModal" tabindex="-1">
<div class="modal-dialog modal-xl">
<div class="modal-content">

<!-- HEADER -->
<div class="modal-header bg-success text-white">
    <h5 class="modal-title">
        <i class="fas fa-user-plus mr-1"></i> Add New Supplier
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
</div>

<!-- BODY -->
<div class="modal-body">
<form>

<div class="row">

    <!-- SUPPLIER CODE -->
    <div class="col-md-4">
        <div class="form-group">
            <label>Supplier Code</label>
            <input type="text" class="form-control" placeholder="Auto or manual (ex: SUP-012)">
        </div>
    </div>

    <!-- COMPANY -->
    <div class="col-md-8">
        <div class="form-group">
            <label>Company Name</label>
            <input type="text" class="form-control" placeholder="Supplier company name">
        </div>
    </div>

    <!-- CONTACT PERSON -->
    <div class="col-md-6">
        <div class="form-group">
            <label>Contact Person</label>
            <input type="text" class="form-control" placeholder="Contact person name">
        </div>
    </div>

    <!-- PHONE -->
    <div class="col-md-6">
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" class="form-control" placeholder="Phone number">
        </div>
    </div>

    <!-- EMAIL -->
    <div class="col-md-6">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" class="form-control" placeholder="Email address">
        </div>
    </div>

    <!-- ADDRESS -->
    <div class="col-md-6">
        <div class="form-group">
            <label>Address</label>
            <input type="text" class="form-control" placeholder="City / Address">
        </div>
    </div>

    <!-- PAYMENT TERM -->
    <div class="col-md-4">
        <div class="form-group">
            <label>Payment Term</label>
            <select class="form-control">
                <option>Cash</option>
                <option>Net 7</option>
                <option>Net 15</option>
                <option>Net 30</option>
            </select>
        </div>
    </div>

    <!-- LEAD TIME -->
    <div class="col-md-4">
        <div class="form-group">
            <label>Lead Time (Days)</label>
            <input type="number" class="form-control" placeholder="Ex: 5">
        </div>
    </div>

    <!-- STATUS -->
    <div class="col-md-4">
        <div class="form-group">
            <label>Status</label>
            <select class="form-control">
                <option value="active" selected>Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- WORK ID CARD -->
    <div class="col-md-12">
        <div class="form-group">
            <label>Upload Work ID Card</label>
            <input type="file" class="form-control">
            <small class="text-muted">
                For internal verification (optional)
            </small>
        </div>
    </div>

</div>

</form>
</div>

<!-- FOOTER -->
<div class="modal-footer">
    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    <button class="btn btn-success">
        Save Supplier
    </button>
</div>

</div>
</div>
</div>

<!-- ADD CATEGORY MODAL -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<!-- HEADER -->
<div class="modal-header bg-info text-white">
    <h5 class="modal-title">
        <i class="fas fa-tags mr-1"></i> Add New Category
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
</div>

<!-- BODY -->
<div class="modal-body">
<form>

<div class="row">

    <!-- CATEGORY CODE -->
    <div class="col-md-4">
        <div class="form-group">
            <label>Category Code</label>
            <input type="text"
                   class="form-control"
                   placeholder="Auto or manual (ex: CAT-001)">
        </div>
    </div>

    <!-- CATEGORY NAME -->
    <div class="col-md-8">
        <div class="form-group">
            <label>Category Name</label>
            <input type="text"
                   class="form-control"
                   placeholder="Category name (ex: Mobile Phone)">
        </div>
    </div>

    <!-- DESCRIPTION -->
    <div class="col-md-12">
        <div class="form-group">
            <label>Description</label>
            <textarea class="form-control"
                      rows="3"
                      placeholder="Optional description"></textarea>
        </div>
    </div>

    <!-- STATUS -->
    <div class="col-md-6">
        <div class="form-group">
            <label>Status</label>
            <select class="form-control">
                <option value="active" selected>Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

</div>

</form>
</div>

<!-- FOOTER -->
<div class="modal-footer">
    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    <button class="btn btn-info">
        Save Category
    </button>
</div>

</div>
</div>
</div>




    {{-- ================= BRAND ACCORDION (ALL BRANDS INSIDE) ================= --}}
    <div id="brandAccordion">

        {{-- ================= BRAND: APPLE ================= --}}
        <div class="card mb-2 brand-card" data-category="mobile-phone">

            <a href="#brandApple" class="text-dark text-decoration-none" data-toggle="collapse">
                <div class="card-body brand-toggle">
                    <div class="row align-items-center">

                        <div class="col-md-1 text-center">
                            <img src="https://logos-world.net/wp-content/uploads/2020/04/Apple-Logo.png" class="img-fluid rounded">
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-1">Apple</h5>
                            <small class="text-muted">Category: Mobile Phone</small>
                        </div>

                        <div class="col-md-3">
                            <span class="badge badge-success mr-2">Active</span>
                            <span class="badge badge-info">1 Supplier</span>
                        </div>

                        <div class="col-md-2 text-right">
                            <i class="fas fa-chevron-down rotate-icon"></i>
                        </div>

                    </div>
                </div>
            </a>

            <div class="collapse" id="brandApple" data-parent="#brandAccordion">
                <div class="card-body border-top">

                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                        <tr>
                            <th>Code</th>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Payment</th>
                            <th>Lead Time</th>
                            <th>Status</th>
                            <th width="120">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>SUP-001</td>
                            <td>Global Tech Supply</td>
                            <td>John Doe</td>
                            <td>012345678</td>
                            <td>info@globaltech.com</td>
                            <td>Phnom Penh</td>
                            <td>Net 30</td>
                            <td>5 Days</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning"data-toggle="modal"data-target="#editSupplierModal"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"data-toggle="modal"data-target="#deleteSupplierModal"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <!-- ===================================================== -->
<!-- EDIT SUPPLIER MODAL -->
<!-- ===================================================== -->
<div class="modal fade" id="editSupplierModal" tabindex="-1">
<div class="modal-dialog modal-xl">
<div class="modal-content">

<!-- HEADER -->
<div class="modal-header bg-warning text-dark">
    <h5 class="modal-title">
        <i class="fas fa-edit mr-1"></i> Edit Supplier
    </h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<!-- BODY -->
<div class="modal-body">
<form>

<div class="row">

    <!-- CODE -->
    <div class="col-md-4">
        <div class="form-group">
            <label>Supplier Code</label>
            <input type="text" class="form-control" value="SUP-001" readonly>
        </div>
    </div>

    <!-- COMPANY -->
    <div class="col-md-8">
        <div class="form-group">
            <label>Company</label>
            <input type="text" class="form-control" value="Global Tech Supply">
        </div>
    </div>

    <!-- CONTACT -->
    <div class="col-md-6">
        <div class="form-group">
            <label>Contact Person</label>
            <input type="text" class="form-control" value="John Doe">
        </div>
    </div>

    <!-- PHONE -->
    <div class="col-md-6">
        <div class="form-group">
            <label>Phone</label>
            <input type="text" class="form-control" value="012345678">
        </div>
    </div>

    <!-- EMAIL -->
    <div class="col-md-6">
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" value="info@globaltech.com">
        </div>
    </div>

    <!-- ADDRESS -->
    <div class="col-md-6">
        <div class="form-group">
            <label>Address</label>
            <input type="text" class="form-control" value="Phnom Penh">
        </div>
    </div>

    <!-- PAYMENT -->
    <div class="col-md-4">
        <div class="form-group">
            <label>Payment Term</label>
            <select class="form-control">
                <option>Cash</option>
                <option selected>Net 30</option>
                <option>Net 15</option>
                <option>Net 7</option>
            </select>
        </div>
    </div>

    <!-- LEAD TIME -->
    <div class="col-md-4">
        <div class="form-group">
            <label>Lead Time</label>
            <input type="text" class="form-control" value="5 Days">
        </div>
    </div>

    <!-- STATUS -->
    <div class="col-md-4">
        <div class="form-group">
            <label>Status</label>
            <select class="form-control">
                <option selected>Active</option>
                <option>Inactive</option>
            </select>
        </div>
    </div>

</div>

</form>
</div>

<!-- FOOTER -->
<div class="modal-footer">
    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    <button class="btn btn-warning">
        <i class="fas fa-save mr-1"></i> Update Supplier
    </button>
</div>

</div>
</div>
</div>


<!-- ===================================================== -->
<!-- DELETE SUPPLIER CONFIRM MODAL -->
<!-- ===================================================== -->
<div class="modal fade" id="deleteSupplierModal" tabindex="-1">
<div class="modal-dialog modal-md modal-dialog-centered">
<div class="modal-content">

<!-- HEADER -->
<div class="modal-header bg-danger text-white">
    <h5 class="modal-title">
        <i class="fas fa-exclamation-triangle mr-1"></i> Confirm Delete
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
</div>

<!-- BODY -->
<div class="modal-body text-center">

    <i class="fas fa-trash fa-3x text-danger mb-3"></i>

    <h5>Are you sure?</h5>
    <p class="text-muted mb-0">
        This supplier will be <strong>permanently deleted</strong>.<br>
        This action cannot be undone.
    </p>

</div>

<!-- FOOTER -->
<div class="modal-footer justify-content-center">
    <button class="btn btn-secondary" data-dismiss="modal">
        Cancel
    </button>
    <button class="btn btn-danger">
        Yes, Delete
    </button>
</div>

</div>
</div>
</div>


        {{-- ================= BRAND: SAMSUNG ================= --}}
        <div class="card mb-2 brand-card" data-category="mobile-phone">

            <a href="#brandSamsung" class="text-dark text-decoration-none" data-toggle="collapse">
                <div class="card-body brand-toggle">
                    <div class="row align-items-center">

                        <div class="col-md-1 text-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/6/61/Samsung_old_logo_before_year_2015.svg" class="img-fluid rounded">
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-1">Samsung</h5>
                            <small class="text-muted">Category: Mobile Phone / Smart TV</small>
                        </div>

                        <div class="col-md-3">
                            <span class="badge badge-success mr-2">Active</span>
                            <span class="badge badge-info">2 Suppliers</span>
                        </div>

                        <div class="col-md-2 text-right">
                            <i class="fas fa-chevron-down rotate-icon"></i>
                        </div>

                    </div>
                </div>
            </a>

            <div class="collapse" id="brandSamsung" data-parent="#brandAccordion">
                <div class="card-body border-top">

                    <table class="table table-sm table-bordered mb-0">
                        <tbody>
                        <tr>
                            <td>SUP-010</td>
                            <td>Asia Samsung Distribution</td>
                            <td>Sok Dara</td>
                            <td>098112233</td>
                            <td>samsung@asia.com</td>
                            <td>Phnom Penh</td>
                            <td>Net 30</td>
                            <td>6 Days</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>SUP-011</td>
                            <td>Electro World Co.</td>
                            <td>Chan Vuthy</td>
                            <td>097998877</td>
                            <td>sales@electroworld.com</td>
                            <td>Siem Reap</td>
                            <td>Cash</td>
                            <td>4 Days</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        {{-- ================= BRAND: LG ================= --}}
        <div class="card mb-2 brand-card" data-category="smart-tv">

            <a href="#brandLG" class="text-dark text-decoration-none" data-toggle="collapse">
                <div class="card-body brand-toggle">
                    <div class="row align-items-center">

                        <div class="col-md-1 text-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/LG_logo_%282014%29.svg/1280px-LG_logo_%282014%29.svg.png" class="img-fluid rounded">
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-1">LG</h5>
                            <small class="text-muted">Category: Smart TV</small>
                        </div>

                        <div class="col-md-3">
                            <span class="badge badge-success mr-2">Active</span>
                            <span class="badge badge-info">1 Supplier</span>
                        </div>

                        <div class="col-md-2 text-right">
                            <i class="fas fa-chevron-down rotate-icon"></i>
                        </div>

                    </div>
                </div>
            </a>

            <div class="collapse" id="brandLG" data-parent="#brandAccordion">
                <div class="card-body border-top">

                    <table class="table table-sm table-bordered mb-0">
                        <tbody>
                        <tr>
                            <td>SUP-020</td>
                            <td>Vision Electronics</td>
                            <td>Kim Sothea</td>
                            <td>095667788</td>
                            <td>vision@lg.com</td>
                            <td>Phnom Penh</td>
                            <td>Net 15</td>
                            <td>5 Days</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>


        {{-- ================= BRAND: DAIKIN ================= --}}
<div class="card mb-2 brand-card" data-category="air-conditioner">

    <a href="#brandDaikin"
       class="text-dark text-decoration-none"
       data-toggle="collapse">

        <div class="card-body brand-toggle">
            <div class="row align-items-center">

                <div class="col-md-1 text-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/DAIKIN_logo.svg/1024px-DAIKIN_logo.svg.png" class="img-fluid rounded">
                </div>

                <div class="col-md-6">
                    <h5 class="mb-1">Daikin</h5>
                    <small class="text-muted">
                        Category: Air Conditioner
                    </small>
                </div>

                <div class="col-md-3">
                    <span class="badge badge-success mr-2">Active</span>
                    <span class="badge badge-info">2 Suppliers</span>
                </div>

                <div class="col-md-2 text-right">
                    <i class="fas fa-chevron-down rotate-icon"></i>
                </div>

            </div>
        </div>
    </a>

    <div class="collapse" id="brandDaikin" data-parent="#brandAccordion">
        <div class="card-body border-top">

            <table class="table table-sm table-bordered mb-0">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Payment</th>
                    <th>Lead Time</th>
                    <th>Status</th>
                    <th width="120">Actions</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>SUP-030</td>
                    <td>Cool Air Engineering</td>
                    <td>Vannak Lim</td>
                    <td>093445566</td>
                    <td>coolair@daikin.com</td>
                    <td>Phnom Penh</td>
                    <td>Net 30</td>
                    <td>7 Days</td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>

                <tr>
                    <td>SUP-031</td>
                    <td>Climate Control Asia</td>
                    <td>Chan Rina</td>
                    <td>092334455</td>
                    <td>climate@asia.com</td>
                    <td>Kandal</td>
                    <td>Net 15</td>
                    <td>6 Days</td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>

                </tbody>
            </table>

        </div>
    </div>
</div>

    </div> {{-- END brandAccordion --}}

</div>
</section>




</div>

@endsection
