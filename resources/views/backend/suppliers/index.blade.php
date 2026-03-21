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
                     <option value="{{ $cat->id }}">{{ $cat->name }}</option>
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

@include('backend.suppliers.add')

{{-- ================= BRAND ACCORDION (ALL BRANDS INSIDE) ================= --}}
<div id="brandAccordion">
    @forelse($brands as $brand)
        @php
            $collapseId = 'brandCollapse' . $brand->id;
            $brandCategoryName = optional($brand->category)->name ?? 'N/A';

            $supplierCount = $brand->suppliers->count();
            $brandStatus = strtolower($brand->status ?? 'active'); // active/inactive
            $brandStatusClass = $brandStatus === 'inactive' ? 'badge-secondary' : 'badge-success';

            // Logo (change column name if yours is different)
            $logo = $brand->logo
                ? asset('storage/' . $brand->logo)
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
                            <h5 class="mb-1">{{ $brand->name }}</h5>
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

                                    $editModalId = 'editSupplierModal' . $supplier->id;
                                    $deleteModalId = 'deleteSupplierModal' . $supplier->id;
                                @endphp

                                <tr>
                                    <td>{{ $supplier->code }}</td>
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
                                                      action="{{ route('suppliers.supplier.update', $supplier->id) }}"
                                                      enctype="multipart/form-data">
                                                   @csrf
                                                   @method('PUT')

                                                   <div class="row">

                                                         <div class="col-md-4">
                                                            <div class="form-group">
                                                               <label>Supplier Code</label>
                                                               <input type="text" class="form-control"
                                                                        name="code"
                                                                        value="{{ $supplier->code }}" readonly>
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

                                                                  @if($supplier->document)
                                                                     <a class="btn btn-sm btn-outline-info"
                                                                        href="{{ asset('storage/' . $supplier->document) }}"
                                                                        target="_blank">
                                                                        <i class="fas fa-file mr-1"></i> View current
                                                                     </a>

                                                                     <small class="text-muted ml-2">
                                                                        {{ basename($supplier->document) }}
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
                                                      action="{{ route('suppliers.supplier.delete', $supplier->id) }}"
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