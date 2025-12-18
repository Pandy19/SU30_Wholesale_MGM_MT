@extends('backend.layouts.master')
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
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Sample</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
<div class="container-fluid">

    {{-- SEARCH & CATEGORY FILTER --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="brandSearch"
                   class="form-control"
                   placeholder="Search brand or supplier">
        </div>
        <div class="col-md-6">
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

                    <div class="d-flex justify-content-end mb-2">
                        <button class="btn btn-sm btn-primary"
                                data-toggle="modal"
                                data-target="#supplierModal"
                                data-mode="add"
                                data-brand-id="1">
                            <i class="fas fa-plus"></i> Add Supplier
                        </button>
                    </div>

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
                                <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

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

            <div class="d-flex justify-content-end mb-2">
                <button class="btn btn-sm btn-primary"
                        data-toggle="modal"
                        data-target="#supplierModal"
                        data-mode="add"
                        data-brand-id="4">
                    <i class="fas fa-plus"></i> Add Supplier
                </button>
            </div>

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
