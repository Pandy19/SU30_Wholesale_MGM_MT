@extends('backend.layouts.master')
@section('title', 'Suppliers | Wholesale MGM')
@section('main-content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <div class="content-header ">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0">Electronics – Brand & Supplier Overview</h1>
               <p class="text-muted mb-0">
                  Click a brand row to view suppliers for each electronic brand.
               </p>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Supliers</a></li>
                  <li class="breadcrumb-item active">Brand Supplier</li>
               </ol>
            </div>
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
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
                  @foreach($categories as $cat)
                     <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                  @endforeach
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

         <!-- HEADER -->
         <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
               <i class="fas fa-industry mr-1"></i> Add New Brand
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
         </div>

         <!-- BODY -->
         <div class="modal-body">
            <form method="POST"
                  action="{{ route('suppliers.brand.store') }}"
                  enctype="multipart/form-data">
               @csrf

               {{-- BRAND LOGO (nice preview + hover upload) --}}
               <div class="form-group text-center">
                  <label class="d-block font-weight-bold">Brand Logo</label>

                  <div class="brand-photo-wrapper mx-auto">
                     {{-- Preview image --}}
                     <img id="brandLogoPreview"
                           src="{{ asset('assets/dist/img/MMOLOGO1.png') }}"
                           alt="Brand Logo Preview"
                           class="brand-photo-preview">


                     {{-- Hover overlay --}}
                     <div class="brand-photo-overlay">
                           <i class="fas fa-camera mr-1"></i>
                           <span id="brandLogoText">Upload</span>
                     </div>

                     {{-- Real file input (hidden but clickable through wrapper) --}}
                     <input type="file"
                              name="logo"
                              id="brandLogoInput"
                              class="brand-photo-input"
                              accept="image/*">
                  </div>

                  @error('logo')
                     <div class="text-danger mt-2">{{ $message }}</div>
                  @enderror

                  <small class="text-muted d-block mt-2">
                     JPG/PNG/WEBP (max 5MB)
                  </small>
               </div>

               <!-- BRAND NAME -->
               <div class="form-group">
                  <label>Brand Name</label>
                  <input type="text"
                         name="brand_name"
                         class="form-control @error('brand_name') is-invalid @enderror"
                         placeholder="Enter brand name"
                         value="{{ old('brand_name') }}"
                         required>
                  @error('brand_name')
                     <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <!-- CATEGORY -->
               <div class="form-group">
                  <label>Category</label>
                  <select name="category_id"
                          class="form-control @error('category_id') is-invalid @enderror"
                          required>
                     <option value="">-- Choose Category --</option>
                     @foreach($categories as $category)
                        <option value="{{ $category->category_id }}"
                           {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                           {{ $category->category_name }}
                        </option>
                     @endforeach
                  </select>
                  @error('category_id')
                     <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <!-- STATUS -->
               <div class="form-group">
                  <label>Status</label>
                  <select name="status"
                          class="form-control @error('status') is-invalid @enderror">
                     <option value="active" {{ old('status','active') == 'active' ? 'selected' : '' }}>
                        Active
                     </option>
                     <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                        Inactive
                     </option>
                  </select>
                  @error('status')
                     <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <!-- FOOTER BUTTONS -->
               <div class="modal-footer px-0 pb-0">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">
                     Cancel
                  </button>
                  <button type="submit" class="btn btn-primary">
                     Submit Brand
                  </button>
               </div>

            </form>
         </div>

      </div>
   </div>
</div>

<div class="modal fade" id="addSupplierModal" tabindex="-1">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="modal-header bg-success text-white">
            <h5 class="modal-title">
               <i class="fas fa-user-plus mr-1"></i> Add New Supplier
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
         </div>

         <div class="modal-body">
            <form method="POST"
                  action="{{ route('suppliers.supplier.store') }}"
                  enctype="multipart/form-data">
               @csrf

               <div class="row">

                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="font-weight-bold text-primary">Select Category</label>
                        <select class="form-control border-primary @error('category_id') is-invalid @enderror" name="category_id" required>
                           <option value="">-- Choose Category --</option>
                           @foreach($categories as $cat)
                              <option value="{{ $cat->category_id }}" {{ old('category_id') == $cat->category_id ? 'selected' : '' }}>
                                 {{ $cat->category_name }}
                              </option>
                           @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="font-weight-bold text-primary">Select Brand</label>
                        <select class="form-control border-primary @error('brand_id') is-invalid @enderror" name="brand_id" required>
                           <option value="">-- Choose Brand --</option>
                           @foreach($allBrands as $b)
                              <option value="{{ $b->brand_id }}" {{ old('brand_id') == $b->brand_id ? 'selected' : '' }}>
                                 {{ $b->brand_name }}
                              </option>
                           @endforeach
                        </select>
                        @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Links this supplier to the specific Brand row on your overview page.</small>
                     </div>
                  </div>

                  <div class="col-md-12"><hr></div>

                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Supplier Code</label>
                        <input type="text"
                               name="supplier_code"
                               class="form-control"
                               value="{{ $nextSupplierCode ?? 'SUP-001' }}"
                               readonly>
                     </div>
                  </div>

                  <div class="col-md-8">
                     <div class="form-group">
                        <label>Company Name</label>
                        <input type="text"
                               name="company_name"
                               class="form-control @error('company_name') is-invalid @enderror"
                               placeholder="Supplier company name"
                               value="{{ old('company_name') }}"
                               required>
                        @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Contact Person</label>
                        <input type="text" name="contact_person" class="form-control"
                               placeholder="Contact person name"
                               value="{{ old('contact_person') }}">
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-control"
                               placeholder="Phone number"
                               value="{{ old('phone') }}">
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control"
                               placeholder="Email address"
                               value="{{ old('email') }}">
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control"
                               placeholder="City / Address"
                               value="{{ old('address') }}">
                     </div>
                  </div>

                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Payment Term</label>
                        <select name="payment_term"
                              class="form-control @error('payment_term') is-invalid @enderror"
                              required>
                           <option value="Immediate">Immediate</option>
                           <option value="Net 7 Days">Net 7 Days</option>
                           <option value="Net 15 Days">Net 15 Days</option>
                           <option value="Net 30 Days">Net 30 Days</option>
                           <option value="Net 60 Days">Net 60 Days</option>
                        </select>
                        @error('payment_term')
                           <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                  </div>

                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Lead Time (Days)</label>
                        <input type="number" name="lead_time_days" class="form-control"
                               placeholder="Ex: 5"
                               value="{{ old('lead_time_days') }}">
                     </div>
                  </div>

                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                           <option value="active" {{ old('status','active') == 'active' ? 'selected' : '' }}>Active</option>
                           <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                     </div>
                  </div>

                  <div class="col-md-12">
                     <div class="form-group">
                        <label>Upload Work ID Card / Business License</label>
                        <input type="file"
                               name="document"
                               class="form-control @error('document') is-invalid @enderror">
                        @error('document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Allowed: JPG/PNG/WEBP/PDF/DOC/DOCX (max 5MB)</small>
                     </div>
                  </div>

               </div>

               <div class="modal-footer px-0 pb-0">
                  <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-success">
                     <i class="fas fa-save mr-1"></i> Save Supplier
                  </button>
               </div>

            </form>
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
            <form method="POST" action="{{ route('suppliers.category.store') }}">
               @csrf

               <div class="row">

                  <!-- CATEGORY CODE (readonly auto) -->
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Category Code</label>
                        <input type="text"
                               name="category_code"
                               class="form-control"
                               value="{{ $nextCategoryCode ?? 'CAT-001' }}"
                               readonly>
                     </div>
                  </div>

                  <!-- CATEGORY NAME -->
                  <div class="col-md-8">
                     <div class="form-group">
                        <label>Category Name</label>
                        <input type="text"
                               name="category_name"
                               class="form-control @error('category_name') is-invalid @enderror"
                               placeholder="Category name (ex: Mobile Phone)"
                               value="{{ old('category_name') }}"
                               required>
                        @error('category_name')
                           <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                  </div>

                  <!-- DESCRIPTION -->
                  <div class="col-md-12">
                     <div class="form-group">
                        <label>Description</label>
                        <textarea name="description"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Optional description">{{ old('description') }}</textarea>
                     </div>
                  </div>

                  <!-- STATUS -->
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                           <option value="active" {{ old('status','active')=='active' ? 'selected' : '' }}>Active</option>
                           <option value="inactive" {{ old('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                           <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                     </div>
                  </div>

               </div>

               <!-- FOOTER -->
               <div class="modal-footer px-0 pb-0">
                  <button class="btn btn-secondary" data-dismiss="modal" type="button">Cancel</button>
                  <button class="btn btn-info" type="submit">
                     Save Category
                  </button>
               </div>

            </form>
         </div>

      </div>
   </div>
</div>




{{-- ================= BRAND ACCORDION (ALL BRANDS INSIDE) ================= --}}
<div id="brandAccordion">
    @forelse($brands as $brand)
        @php
            $collapseId = 'brandCollapse' . $brand->brand_id;
            $brandCategoryName = optional($brand->category)->category_name ?? 'N/A';

            $supplierCount = $brand->suppliers->count();
            $brandStatus = strtolower($brand->status ?? 'active'); // active/inactive
            $brandStatusClass = $brandStatus === 'inactive' ? 'badge-secondary' : 'badge-success';

            // Logo (change column name if yours is different)
            $logo = $brand->logo_path
                ? asset('storage/' . $brand->logo_path)
                : asset('backend/images/no-image.png'); // create a placeholder if you want
        @endphp

        <div class="card mb-2 brand-card" data-category="{{ $brand->category_id }}">
            <a href="#{{ $collapseId }}" class="text-dark text-decoration-none" data-toggle="collapse">
                <div class="card-body brand-toggle">
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center">
                            <img src="{{ $logo }}" class="img-fluid rounded" style="max-height:45px;">
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-1">{{ $brand->brand_name }}</h5>
                            <small class="text-muted">Category: {{ $brandCategoryName }}</small>
                        </div>

                        <div class="col-md-3">
                            <span class="badge {{ $brandStatusClass }} mr-2">
                                {{ ucfirst($brandStatus) }}
                            </span>
                            <span class="badge badge-info">
                                {{ $supplierCount }} {{ $supplierCount === 1 ? 'Supplier' : 'Suppliers' }}
                            </span>
                        </div>

                        <div class="col-md-2 text-right">
                            <i class="fas fa-chevron-down rotate-icon"></i>
                        </div>
                    </div>
                </div>
            </a>

            <div class="collapse" id="{{ $collapseId }}" data-parent="#brandAccordion">
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
                            @forelse($brand->suppliers as $supplier)
                                @php
                                    $sStatus = strtolower($supplier->status ?? 'active');
                                    $sStatusClass = $sStatus === 'inactive' ? 'badge-secondary' : 'badge-success';

                                    $editModalId = 'editSupplierModal' . $supplier->supplier_id;
                                    $deleteModalId = 'deleteSupplierModal' . $supplier->supplier_id;
                                @endphp

                                <tr>
                                    <td>{{ $supplier->supplier_code }}</td>
                                    <td>{{ $supplier->company_name }}</td>
                                    <td>{{ $supplier->contact_person }}</td>
                                    <td>{{ $supplier->phone }}</td>
                                    <td>{{ $supplier->email }}</td>
                                    <td>{{ $supplier->address }}</td>
                                    <td>{{ $supplier->payment_term }}</td>
                                    <td>{{ $supplier->lead_time_days ? $supplier->lead_time_days.' Days' : '' }}</td>
                                    <td><span class="badge {{ $sStatusClass }}">{{ ucfirst($sStatus) }}</span></td>

                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning"
                                                data-toggle="modal"
                                                data-target="#{{ $editModalId }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-danger"
                                                data-toggle="modal"
                                                data-target="#{{ $deleteModalId }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- ================= EDIT SUPPLIER MODAL (PER ROW) ================= --}}
                                 <div class="modal fade" id="{{ $editModalId }}" tabindex="-1">
                                    <div class="modal-dialog modal-xl">
                                       <div class="modal-content">

                                             <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title">
                                                   <i class="fas fa-edit mr-1"></i> Edit Supplier
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                             </div>

                                             <div class="modal-body">
                                                <form method="POST"
                                                      action="{{ route('suppliers.supplier.update', $supplier->supplier_id) }}"
                                                      enctype="multipart/form-data">
                                                   @csrf
                                                   @method('PUT')

                                                   <div class="row">

                                                         <div class="col-md-4">
                                                            <div class="form-group">
                                                               <label>Supplier Code</label>
                                                               <input type="text" class="form-control"
                                                                        name="supplier_code"
                                                                        value="{{ $supplier->supplier_code }}" readonly>
                                                            </div>
                                                         </div>

                                                         <div class="col-md-8">
                                                            <div class="form-group">
                                                               <label>Company</label>
                                                               <input type="text"
                                                                        class="form-control"
                                                                        name="company_name"
                                                                        value="{{ old('company_name', $supplier->company_name) }}"
                                                                        required>
                                                            </div>
                                                         </div>

                                                         <div class="col-md-6">
                                                            <div class="form-group">
                                                               <label>Contact Person</label>
                                                               <input type="text"
                                                                        class="form-control"
                                                                        name="contact_person"
                                                                        value="{{ old('contact_person', $supplier->contact_person) }}">
                                                            </div>
                                                         </div>

                                                         <div class="col-md-6">
                                                            <div class="form-group">
                                                               <label>Phone</label>
                                                               <input type="text"
                                                                        class="form-control"
                                                                        name="phone"
                                                                        value="{{ old('phone', $supplier->phone) }}">
                                                            </div>
                                                         </div>

                                                         <div class="col-md-6">
                                                            <div class="form-group">
                                                               <label>Email</label>
                                                               <input type="email"
                                                                        class="form-control"
                                                                        name="email"
                                                                        value="{{ old('email', $supplier->email) }}">
                                                            </div>
                                                         </div>

                                                         <div class="col-md-6">
                                                            <div class="form-group">
                                                               <label>Address</label>
                                                               <input type="text"
                                                                        class="form-control"
                                                                        name="address"
                                                                        value="{{ old('address', $supplier->address) }}">
                                                            </div>
                                                         </div>

                                                         {{-- ✅ Payment Term (MUST match ENUM exactly) --}}
                                                         <div class="col-md-4">
                                                            <div class="form-group">
                                                               <label>Payment Term</label>
                                                               @php
                                                                     $terms = ['Immediate','Net 7 Days','Net 15 Days','Net 30 Days','Net 60 Days'];
                                                                     $selected = old('payment_term', $supplier->payment_term);
                                                               @endphp
                                                               <select class="form-control" name="payment_term" required>
                                                                     @foreach($terms as $term)
                                                                        <option value="{{ $term }}" {{ $selected === $term ? 'selected' : '' }}>
                                                                           {{ $term }}
                                                                        </option>
                                                                     @endforeach
                                                               </select>
                                                            </div>
                                                         </div>

                                                         <div class="col-md-4">
                                                            <div class="form-group">
                                                               <label>Lead Time (Days)</label>
                                                               <input type="number"
                                                                        class="form-control"
                                                                        name="lead_time_days"
                                                                        value="{{ old('lead_time_days', $supplier->lead_time_days) }}">
                                                            </div>
                                                         </div>

                                                         <div class="col-md-4">
                                                            <div class="form-group">
                                                               <label>Status</label>
                                                               @php
                                                                  $selectedStatus = old('status', $supplier->status); // ✅ from DB
                                                               @endphp

                                                               <select class="form-control" name="status" required>
                                                                  <option value="active" {{ $selectedStatus === 'active' ? 'selected' : '' }}>Active</option>
                                                                  <option value="inactive" {{ $selectedStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                               </select>
                                                            </div>
                                                         </div>

                                                         {{-- ✅ Optional: Update license --}}
                                                         <div class="col-md-12">
                                                            <div class="form-group">
                                                               <label>Update Work ID Card / Business License</label>

                                                               <input type="file"
                                                                     name="document"
                                                                     class="form-control"
                                                                     accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx"
                                                                     onchange="previewSupplierDoc('{{ $editModalId }}', this)">

                                                               {{-- OLD FILE --}}
                                                               <div class="mt-3">
                                                                  <div class="small font-weight-bold text-muted mb-1">Current file:</div>

                                                                  @if($supplier->document_path)
                                                                     <a class="btn btn-sm btn-outline-info"
                                                                        href="{{ asset('storage/' . $supplier->document_path) }}"
                                                                        target="_blank">
                                                                        <i class="fas fa-file mr-1"></i> View current
                                                                     </a>

                                                                     <small class="text-muted ml-2">
                                                                        {{ basename($supplier->document_path) }}
                                                                     </small>
                                                                  @else
                                                                     <span class="text-muted">No file uploaded</span>
                                                                  @endif
                                                               </div>

                                                               {{-- NEW FILE PREVIEW --}}
                                                               <div class="mt-3" id="newDocPreviewWrap-{{ $editModalId }}" style="display:none;">
                                                                  <div class="small font-weight-bold text-muted mb-1">New selected file:</div>

                                                                  <div class="d-flex align-items-center">
                                                                     <img id="newDocImg-{{ $editModalId }}"
                                                                        src=""
                                                                        alt="New preview"
                                                                        style="max-height:60px; display:none;"
                                                                        class="border rounded p-1 mr-2">

                                                                     <a id="newDocLink-{{ $editModalId }}"
                                                                        href="#"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-success"
                                                                        style="display:none;">
                                                                        <i class="fas fa-eye mr-1"></i> Preview new
                                                                     </a>

                                                                     <small id="newDocName-{{ $editModalId }}" class="text-muted ml-2"></small>
                                                                  </div>

                                                                  <small class="text-muted d-block mt-1">
                                                                     (This is only preview. It will save after you click <b>Update Supplier</b>.)
                                                                  </small>
                                                               </div>

                                                            </div>
                                                         </div>

                                                   </div>

                                                   <div class="text-right">
                                                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                         <button type="submit" class="btn btn-warning">
                                                            <i class="fas fa-save mr-1"></i> Update Supplier
                                                         </button>
                                                   </div>

                                                </form>
                                             </div>

                                       </div>
                                    </div>
                                 </div>


                                {{-- ================= DELETE SUPPLIER MODAL (PER ROW) ================= --}}
                                <div class="modal fade" id="{{ $deleteModalId }}" tabindex="-1">
                                    <div class="modal-dialog modal-md modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Confirm Delete
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body text-center">
                                                <i class="fas fa-trash fa-3x text-danger mb-3"></i>
                                                <h5>Are you sure?</h5>
                                                <p class="text-muted mb-0">
                                                    This supplier will be <strong>permanently deleted</strong>.<br>
                                                    This action cannot be undone.
                                                </p>
                                            </div>

                                            <div class="modal-footer justify-content-center">
                                                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                                                {{-- DELETE route later --}}
                                                <form method="POST"
                                                      action="{{ route('suppliers.supplier.delete', $supplier->supplier_id) }}"
                                                      class="m-0">
                                                   @csrf
                                                   @method('DELETE')
                                                   <button type="submit" class="btn btn-danger">
                                                      Yes, Delete
                                                   </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        No suppliers found for this brand.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @empty
        <div class="alert alert-info">
            No brands found.
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $brands->links() }}
</div>

         {{-- END brandAccordion --}}
      </div>
   </section>
</div>
@endsection