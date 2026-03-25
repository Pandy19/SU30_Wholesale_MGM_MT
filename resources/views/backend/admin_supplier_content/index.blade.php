@extends('backend.layouts.master')

@section('main-content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Supplier Content Moderation</h1>
                    <p class="text-muted">Manage product submissions and visibility for all active suppliers.</p>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        {{-- LEFT SIDE: Title --}}
                        <div class="col-md-7">
                            <h5 class="card-title font-weight-bold mb-0">Registered Suppliers</h5>
                            <small class="text-muted">Direct management of supplier offers</small>
                        </div>

                        {{-- RIGHT SIDE: Search Filter --}}
                        <div class="col-md-5 text-right">
                            <label class="font-weight-bold text-muted small text-uppercase mb-2 d-block text-right">Search Filter</label>
                            <div class="input-group shadow-none border rounded ml-auto" style="overflow: hidden; background: #f8f9fa; width: 100%;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-0">
                                        <i class="fas fa-search text-primary small"></i>
                                    </span>
                                </div>
                                <input type="text" id="ajaxSearch" name="search" class="form-control border-0 bg-white" 
                                       placeholder="Search Supplier Code or Company Name..." 
                                       value="{{ request('search') }}"
                                       style="height: 40px; font-size: 0.9rem;">
                                <div id="searchLoader" class="ml-2 align-self-center pr-2" style="display: none;">
                                    <i class="fas fa-spinner fa-spin text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="80" class="text-center">Avatar</th>
                                    <th>Supplier Code</th>
                                    <th>Company Name</th>
                                    <th>Email / Contact</th>
                                    <th class="text-center">Active Posts</th>
                                    <th width="200" class="text-center">Management</th>
                                </tr>
                            </thead>
                            <tbody id="supplierTableBody">
                                @include('backend.admin_supplier_content.partials.supplier_table')
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-0 py-3 d-flex align-items-center">
                    {{-- ITEM INFO ON LEFT --}}
                    <div id="itemInfo">
                        <small class="text-muted font-weight-bold">
                            Showing {{ $suppliers->firstItem() ?? 0 }}-{{ $suppliers->lastItem() ?? 0 }} of {{ $suppliers->total() }} Suppliers
                        </small>
                    </div>

                    {{-- PAGINATION PUSHED TO RIGHT --}}
                    <div id="paginationContainer" class="ml-auto">
                        @include('backend.admin_supplier_content.partials.pagination')
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

{{-- Supplier Products Modal --}}
<div class="modal fade" id="supplierProductsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-white border-bottom-0 pt-4 px-4">
                <div>
                    <h4 class="modal-title font-weight-bold" id="modalSupplierName">Supplier Products</h4>
                    <p class="text-muted small mb-0">Edit, hide, or remove specific product offers from this supplier.</p>
                </div>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th width="80">Product</th>
                                <th>Name / Info</th>
                                <th>Details</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th width="150" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productsList">
                            {{-- AJAX Content --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light px-4" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Global Action Forms --}}
<form id="globalDeleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<form id="globalToggleForm" method="POST" style="display:none;">
    @csrf
</form>

@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Toast Configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Show Laravel Flash Messages
    @if(session('success'))
        Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
    @endif
    @if(session('error'))
        Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
    @endif

    // --- AJAX LIVE SEARCH ---
    let searchTimer;
    $('#ajaxSearch').on('keyup', function() {
        clearTimeout(searchTimer);
        let query = $(this).val();
        
        searchTimer = setTimeout(function() {
            performSearch(query);
        }, 400); // 400ms debounce
    });

    function performSearch(query, page = 1) {
        $('#searchLoader').show();
        
        $.ajax({
            url: "{{ route('admin.supplier_content.index') }}",
            type: "GET",
            data: { 
                search: query, 
                page: page,
                per_page: $('select[name="per_page"]').val() || 10
            },
            success: function(data) {
                $('#supplierTableBody').html(data.html);
                $('#paginationContainer').html(data.pagination);
                $('#itemInfo').html(`<small class="text-muted font-weight-bold">Showing ${data.firstItem}-${data.lastItem} of ${data.total} Suppliers</small>`);
                $('#searchLoader').hide();
            },
            error: function() {
                $('#searchLoader').hide();
                Toast.fire({ icon: 'error', title: "Search failed." });
            }
        });
    }

    // Handle pagination clicks without reload
    $(document).on('click', '#paginationContainer .page-link', function(e) {
        e.preventDefault();
        let url = new URL($(this).attr('href'));
        let page = url.searchParams.get('page');
        let query = $('#ajaxSearch').val();
        performSearch(query, page);
    });

    // Handle per_page change without reload
    $(document).on('change', '#paginationContainer select[name="per_page"]', function(e) {
        e.preventDefault();
        let query = $('#ajaxSearch').val();
        performSearch(query, 1);
    });

    // --- PRODUCT MANAGEMENT MODAL ---
    function loadSupplierProducts(supplierId) {
        $('#productsList').html('<tr><td colspan="7" class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><br>Fetching supplier products...</td></tr>');
        $('#supplierProductsModal').modal('show');

        $.ajax({
            url: "{{ url('/admin/supplier_content/products') }}/" + supplierId,
            type: "GET",
            success: function(response) {
                $('#modalSupplierName').text(response.supplier_name + ' - Product Submission List');
                let html = '';
                
                if (response.offers.length > 0) {
                    response.offers.forEach(function(offer) {
                        let statusClass = offer.status === 'available' ? 'badge-success' : 
                                         (offer.status === 'limited' ? 'badge-warning' : 
                                         (offer.status === 'hidden' ? 'badge-danger' : 'badge-secondary'));
                        
                        let hiddenRowClass = offer.status === 'hidden' ? 'table-danger-light' : '';
                        let eyeIcon = offer.status === 'hidden' ? 'fa-eye' : 'fa-eye-slash';
                        let toggleTitle = offer.status === 'hidden' ? 'Unhide (Make Visible)' : 'Hide (Make Invisible)';
                        let toggleBtnClass = offer.status === 'hidden' ? 'btn-success' : 'btn-info';

                        html += `
                            <tr class="${hiddenRowClass}">
                                <td>
                                    <img src="${offer.image}" class="img-thumbnail" style="width:60px; height:60px; object-fit:contain;">
                                </td>
                                <td>
                                    <div class="font-weight-bold">${offer.product_name}</div>
                                    <small class="text-muted">Ref: #${offer.id}</small>
                                </td>
                                <td>
                                    <small class="d-block"><strong>Brand:</strong> ${offer.brand}</small>
                                    <small class="d-block"><strong>Category:</strong> ${offer.category}</small>
                                </td>
                                <td class="font-weight-bold text-primary">$${offer.price}</td>
                                <td>${offer.qty}</td>
                                <td>
                                    <span class="badge ${statusClass}">${offer.status.toUpperCase()}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="${offer.edit_url}" class="btn btn-sm btn-warning" title="Edit Content">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm ${toggleBtnClass}" 
                                                onclick="confirmToggle('${offer.toggle_url}', '${offer.status}')" title="${toggleTitle}">
                                            <i class="fas ${eyeIcon}"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete('${offer.delete_url}')" title="Mark Inactive">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="7" class="text-center py-5">No active products found for this supplier.</td></tr>';
                }
                
                $('#productsList').html(html);
            }
        });
    }

    function confirmToggle(url, currentStatus) {
        let action = currentStatus === 'hidden' ? 'UNHIDE' : 'HIDE';
        let msg = currentStatus === 'hidden' 
            ? "This product will become visible to all users in the marketplace."
            : "This product will be hidden from the marketplace but kept in your records.";

        Swal.fire({
            title: `Are you sure you want to ${action}?`,
            text: msg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: `Yes, ${action} it!`,
            cancelButtonText: 'No, Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#globalToggleForm').attr('action', url).submit();
            }
        });
    }

    function confirmDelete(url) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This product will be marked as INACTIVE and removed from this list. It won't be deleted from the database but won't show here anymore.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes, Mark Inactive',
            cancelButtonText: 'No, Keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#globalDeleteForm').attr('action', url).submit();
            }
        });
    }
</script>

<style>
    .table-danger-light { background-color: rgba(220, 53, 69, 0.04); }
    .align-middle td { vertical-align: middle !important; }
    .table thead th { border-top: none; }
    
    /* Ensure pagination container stays right-aligned */
    #paginationContainer .pagination-container {
        justify-content: flex-end !important;
    }
</style>
@endsection
