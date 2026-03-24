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
                  placeholder="Search...">
            </div>
            <div class="col-md-2">
               <select id="categoryFilter" class="form-control shadow-xs">
                  <option value="">All Categories (Default)</option>
                  @foreach($categories as $cat)
                     <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                  @endforeach
               </select>
            </div>
            <div class="col-md-2">
               <select id="brandFilter" class="form-control shadow-xs">
                  <option value="">All Brands (Default)</option>
                  @foreach($allBrands as $brand)
                     <option value="{{ $brand->id }}" data-category="{{ $brand->category_id }}">
                        {{ $brand->name }}
                     </option>
                  @endforeach
               </select>
            </div>
            <div class="col-md-4 text-right">
               @if(!in_array(auth()->user()->role, ['inspector', 'accountant']))
               <div class="btn-group">
                  <button class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#addBrandModal">
                     <i class="fas fa-plus mr-1"></i> Brand
                  </button>
                  <button class="btn btn-success btn-sm shadow-sm" data-toggle="modal" data-target="#addSupplierModal">
                     <i class="fas fa-user-plus mr-1"></i> Supplier
                  </button>
                  <div class="btn-group">
                     <button type="button" class="btn btn-info btn-sm shadow-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-tags mr-1"></i> Category
                     </button>
                     <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addCategoryModal">
                           <i class="fas fa-plus-circle mr-2 text-primary"></i>Add Category
                        </a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteCategorySelectionModal">
                           <i class="fas fa-trash-alt mr-2 text-danger"></i>Delete Category
                        </a>
                     </div>
                  </div>
               </div>
               @endif
            </div>
         </div>

@include('backend.suppliers.add')
@include('backend.suppliers.edit_brand')
@include('backend.suppliers.edit_supplier')

{{-- CATEGORY DELETE SELECTION MODAL (Step 1) --}}
<div class="modal fade" id="deleteCategorySelectionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-trash-alt mr-2"></i>Delete Category (Step 1/2)</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group">
                    <label class="font-weight-bold">Select Category to Remove:</label>
                    <select id="categoryToDelete" class="form-control form-control-lg">
                        <option value="">-- Choose Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" data-name="{{ $cat->name }}" data-brands="{{ $cat->brands->count() }}">
                                {{ $cat->name }} ({{ $cat->brands->count() }} Brands)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="alert alert-info small mt-3">
                    <i class="fas fa-info-circle mr-1"></i> Note: You can only delete categories that don't have active suppliers.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light border px-4" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger px-4" onclick="proceedToCategoryConfirm()">Next Step <i class="fas fa-arrow-right ml-1"></i></button>
            </div>
        </div>
    </div>
</div>

{{-- CATEGORY FINAL CONFIRMATION MODAL (Step 2) --}}
<div class="modal fade" id="deleteCategoryFinalModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold text-warning"><i class="fas fa-exclamation-triangle mr-2"></i>FINAL WARNING (Step 2/2)</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <i class="fas fa-skull-crossbones fa-4x text-danger animate__animated animate__pulse animate__infinite"></i>
                </div>
                <h4 class="font-weight-bold">Are you ABSOLUTELY sure?</h4>
                <p class="text-muted">You are about to delete the category <strong id="finalCategoryName" class="text-danger"></strong>.</p>
                <div class="bg-light p-3 rounded border text-left small">
                    <ul class="mb-0">
                        <li>All associated <strong>Brands</strong> will be deleted.</li>
                        <li>All <strong>Brand Logos</strong> in storage will be permanently erased.</li>
                        <li>This action <strong>CANNOT</strong> be undone.</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer justify-content-center pb-4 border-0">
                <form id="finalCategoryDeleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-light border px-4 mr-2" data-dismiss="modal">Wait, Stop!</button>
                    <button type="submit" class="btn btn-danger btn-lg px-5 shadow">YES, DELETE EVERYTHING</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
            <div class="position-relative">
                <a href="#{{ $collapseId }}" class="text-dark text-decoration-none" data-toggle="collapse">
                    <div class="card-body brand-toggle pr-5">
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
                {{-- Absolute positioned action buttons --}}
                @if(in_array(auth()->user()->role, ['owner', 'admin']))
                <div style="position: absolute; right: 60px; top: 50%; transform: translateY(-50%); z-index: 10;">
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-warning border-0 mr-1" 
                                data-toggle="modal" 
                                data-target="#editBrandModal{{ $brand->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger border-0" 
                                data-toggle="modal" 
                                data-target="#deleteBrandModal{{ $brand->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                @elseif(auth()->user()->role === 'staff')
                {{-- Staff can only edit --}}
                <div style="position: absolute; right: 60px; top: 50%; transform: translateY(-50%); z-index: 10;">
                    <button class="btn btn-sm btn-outline-warning border-0" 
                            data-toggle="modal" 
                            data-target="#editBrandModal{{ $brand->id }}">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                @endif
            </div>


            {{-- BRAND DELETE MODAL (Step 1) --}}
            <div class="modal fade" id="deleteBrandModal{{ $brand->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title font-weight-bold"><i class="fas fa-trash-alt mr-2"></i>Delete Brand (Step 1/2)</h5>
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body text-center p-4">
                            <p>You are about to remove the brand: <br><strong class="h4 text-primary">{{ $brand->name }}</strong></p>
                            @if($supplierCount > 0)
                                <div class="alert alert-warning border-warning mt-3">
                                    <i class="fas fa-ban mr-1"></i> <strong>Locked:</strong> This brand has {{ $supplierCount }} suppliers and cannot be deleted.
                                </div>
                            @else
                                <p class="text-muted mt-3">This will prepare the brand for permanent removal.</p>
                            @endif
                        </div>
                        <div class="modal-footer justify-content-center border-0">
                            <button type="button" class="btn btn-light border px-4" data-dismiss="modal">Cancel</button>
                            @if($supplierCount == 0)
                                <button type="button" class="btn btn-danger px-4" 
                                        onclick="$('#deleteBrandModal{{ $brand->id }}').modal('hide'); setTimeout(function(){ $('#finalBrandDeleteModal{{ $brand->id }}').modal('show'); }, 400);">
                                    Next Step <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- BRAND FINAL DELETE MODAL (Step 2) --}}
            <div class="modal fade" id="finalBrandDeleteModal{{ $brand->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title font-weight-bold text-warning"><i class="fas fa-exclamation-triangle mr-2"></i>FINAL CONFIRMATION (Step 2/2)</h5>
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body text-center p-5">
                            <i class="fas fa-exclamation-circle fa-4x text-danger animate__animated animate__shakeX mb-4"></i>
                            <h4 class="font-weight-bold">Delete "{{ $brand->name }}"?</h4>
                            <p class="text-muted">This will permanently delete the brand and its <strong>Logo File</strong> from the server storage.</p>
                            <div class="alert alert-danger py-2 small">
                                <i class="fas fa-info-circle mr-1"></i> THIS ACTION CANNOT BE UNDONE
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center pb-4 border-0">
                            <form action="{{ route('suppliers.brand.delete', $brand->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-light border px-4 mr-2" data-dismiss="modal">Stop, Go Back</button>
                                <button type="submit" class="btn btn-danger btn-lg px-5 shadow">YES, DELETE BRAND</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="collapse" id="{{ $collapseId }}" data-parent="#brandAccordion">
                <div class="card-body border-top">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th width="50">Avatar</th>
                                <th>Code</th>
                                <th>Company</th>
                                <th>Contact</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Payment</th>
                                <th>Lead Time</th>
                                <th>Status</th>
                                @if(auth()->user()->role !== 'inspector')
                                <th width="120">Actions</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($brand->suppliers as $supplier)
                                @php
                                    $sStatus = strtolower($supplier->status ?? 'active');
                                    $sStatusClass = $sStatus === 'inactive' ? 'badge-secondary' : 'badge-success';

                                    $editModalId = 'editSupplierModal' . $supplier->id;
                                    $deleteModalId = 'deleteSupplierModal' . $supplier->id;

                                    $userProfile = $supplier->user && $supplier->user->profile_picture 
                                        ? asset('storage/' . $supplier->user->profile_picture) 
                                        : asset('assets/dist/img/MMOLOGO1.png');
                                @endphp

                                <tr>
                                    <td class="text-center">
                                        <img src="{{ $userProfile }}" class="img-circle elevation-1" style="width:35px; height:35px; object-fit:cover;">
                                    </td>
                                    <td>{{ $supplier->code }}</td>
                                    <td>{{ $supplier->company_name }}</td>
                                    <td>{{ $supplier->contact_person }}</td>
                                    <td>{{ $supplier->phone }}</td>
                                    <td>{{ $supplier->email }}</td>
                                    <td>{{ $supplier->address }}</td>
                                    <td>{{ $supplier->payment_term }}</td>
                                    <td>{{ $supplier->lead_time_days ? $supplier->lead_time_days.' Days' : '' }}</td>
                                    <td><span class="badge {{ $sStatusClass }}">{{ ucfirst($sStatus) }}</span></td>

                                    @if(in_array(auth()->user()->role, ['owner', 'admin']))
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
                                    @elseif(auth()->user()->role === 'staff')
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning"
                                                data-toggle="modal"
                                                data-target="#{{ $editModalId }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                    @endif
                                </tr>
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

@push('scripts')
<script>
$(document).ready(function() {
    const brandSearch = $('#brandSearch');
    const categoryFilter = $('#categoryFilter');
    const brandFilter = $('#brandFilter');
    const brandCards = $('.brand-card');

    function performFilter() {
        const searchText = brandSearch.val().toLowerCase();
        const selectedCat = categoryFilter.val();
        const selectedBrandId = brandFilter.val();

        brandCards.each(function() {
            const card = $(this);
            const cardText = card.text().toLowerCase();
            const cardCatId = card.data('category');
            
            // Extract Brand ID from collapse link or similar
            const brandLink = card.find('a[data-toggle="collapse"]').attr('href');
            const cardBrandId = brandLink ? brandLink.replace('#brandCollapse', '') : null;

            const matchesSearch = searchText === '' || cardText.includes(searchText);
            const matchesCat = selectedCat === '' || cardCatId == selectedCat;
            const matchesBrand = selectedBrandId === '' || cardBrandId == selectedBrandId;

            if (matchesSearch && matchesCat && matchesBrand) {
                card.fadeIn(200);
            } else {
                card.hide();
            }
        });
    }

    // Filter Logic
    brandSearch.on('keyup', performFilter);
    
    categoryFilter.on('change', function() {
        const catId = $(this).val();
        
        // Synchronize Brand Dropdown: Show only brands in this category
        brandFilter.val(''); // Reset brand selection when category changes
        
        if (catId) {
            brandFilter.find('option').each(function() {
                const opt = $(this);
                const optCat = opt.data('category');
                if (opt.val() === '' || optCat == catId) {
                    opt.show();
                } else {
                    opt.hide();
                }
            });
        } else {
            brandFilter.find('option').show();
        }
        
        performFilter();
    });

    brandFilter.on('change', performFilter);
});

function proceedToCategoryConfirm() {
    const select = $('#categoryToDelete');
    const id = select.val();
    const name = select.find('option:selected').data('name');
    
    if (!id) {
        alert('Please select a category first.');
        return;
    }

    $('#finalCategoryName').text(name);
    $('#finalCategoryDeleteForm').attr('action', '/suppliers/category/' + id);
    
    // Hide current modal and show final one
    $('#deleteCategorySelectionModal').modal('hide');
    setTimeout(function() {
        $('#deleteCategoryFinalModal').modal('show');
    }, 400); // Small delay for smooth transition
}
</script>
@endpush