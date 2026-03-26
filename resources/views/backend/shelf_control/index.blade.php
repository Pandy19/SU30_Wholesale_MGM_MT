@extends('backend.layouts.master')
@section('title', 'Shelf Control | Wholesale MGM')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    .progress { height: 10px; }
    .inactive-shelf { opacity: 0.6; background-color: #f8f9fa; }
</style>
@endpush

@section('main-content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Shelf Control</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Shelf Control</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row mb-3 text-right">
                <div class="col-12">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createShelfModal">
                        <i class="fas fa-plus"></i> Create New Shelf
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-dark">
                    <h3 class="card-title">Storage Shelves Overview</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Shelf Name</th>
                                <th>Category / Brand</th>
                                <th>Status</th>
                                <th>Capacity Status</th>
                                <th>Products</th>
                                <th style="width: 100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shelves as $shelf)
                                @php
                                    $percent = ($shelf->total_quantity / $shelf->max_capacity) * 100;
                                    $badgeClass = $percent >= 100 ? 'badge-danger' : ($percent >= 80 ? 'badge-warning' : 'badge-success');
                                    $progressClass = $percent >= 100 ? 'bg-danger' : ($percent >= 80 ? 'bg-warning' : 'bg-success');
                                    $isInactive = $shelf->status === 'inactive';
                                @endphp
                                <tr class="{{ $isInactive ? 'inactive-shelf' : '' }}">
                                    <td>
                                        <strong>{{ $shelf->name }}</strong><br>
                                        <small class="text-muted">{{ $shelf->code }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $shelf->category->name }}</span><br>
                                        <span class="badge badge-secondary">{{ $shelf->brand->name }}</span>
                                    </td>
                                    <td>
                                        @if($isInactive)
                                            <span class="badge badge-danger">Inactive</span>
                                        @else
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 mr-2" style="height: 8px;">
                                                <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ min($percent, 100) }}%"></div>
                                            </div>
                                            <span class="badge {{ $badgeClass }}">{{ $shelf->total_quantity }} / {{ $shelf->max_capacity }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#viewProductsModal{{ $shelf->id }}">
                                            <i class="fas fa-list"></i> {{ $shelf->stocks->count() }} Product(s)
                                        </button>

                                        <!-- Products Modal -->
                                        <div class="modal fade" id="viewProductsModal{{ $shelf->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content text-left">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title">Products in {{ $shelf->name }}</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body p-0">
                                                        <table class="table table-striped mb-0 align-middle">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th style="width: 80px">Image</th>
                                                                    <th>Product Name</th>
                                                                    <th>SKU</th>
                                                                    <th>Quantity</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($shelf->stocks as $stock)
                                                                    <tr>
                                                                        <td class="py-1">
                                                                            <img src="{{ $stock->product->image ? asset('storage/' . $stock->product->image) : asset('assets/dist/img/MMOLOGO1.png') }}" 
                                                                                 alt="Product" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;"
                                                                                 onerror="this.src='{{ asset('assets/dist/img/MMOLOGO1.png') }}'">
                                                                        </td>
                                                                        <td class="align-middle">{{ $stock->product->name }}</td>
                                                                        <td class="align-middle"><code>{{ $stock->product->sku }}</code></td>
                                                                        <td class="align-middle"><span class="badge badge-primary">{{ $stock->quantity }}</span></td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="3" class="text-center py-4">No products currently stored on this shelf.</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($shelf->status === 'active')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete-shelf" 
                                                    data-toggle="modal" 
                                                    data-target="#deleteConfirmModal"
                                                    data-id="{{ $shelf->id }}"
                                                    data-name="{{ $shelf->name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-secondary disabled" title="Already Inactive">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No shelves found. Create one to get started.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Create Shelf Modal -->
<div class="modal fade" id="createShelfModal" tabindex="-1" role="dialog" aria-labelledby="createShelfModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('shelf_control.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createShelfModalLabel">Add New Shelf</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">
                        Select a category and brand. The system will automatically generate the next shelf name (e.g., Shelf A4) and code.
                    </p>

                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id" class="form-control select2" style="width: 100%;" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <label for="brand_id">Brand</label>
                        <select name="brand_id" id="brand_id" class="form-control select2" style="width: 100%;" required>
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <label>Default Limit</label>
                        <input type="text" class="form-control" value="50" disabled>
                        <small class="text-info">* Max capacity is fixed at 50 for new shelves.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate Shelf</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteShelfForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Deactivation</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3.5rem;"></i>
                    <h4 class="font-weight-bold">Are you sure?</h4>
                    <p class="mb-1">You are about to set <strong id="deleteShelfName"></strong> to <span class="badge badge-danger">Inactive</span>.</p>
                    <p class="text-muted small">This shelf will no longer be available for selection but will remain in the database records.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4">Yes, Deactivate It</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Handle Delete Modal Data
        $('.btn-delete-shelf').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#deleteShelfName').text(name);
            $('#deleteShelfForm').attr('action', '{{ url("/shelf_control") }}/' + id);
        });
    });
</script>
@endpush
