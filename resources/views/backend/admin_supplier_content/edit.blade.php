@extends('backend.layouts.master')

@section('main-content')
<style>
    .card-modern { border: none; border-radius: 12px; transition: all 0.3s; }
    .section-title { font-size: 1.1rem; font-weight: 700; color: #2c3e50; border-bottom: 2px solid #007bff; padding-bottom: 8px; display: inline-block; margin-bottom: 20px; }
    .form-group label { font-weight: 600; color: #5a6c7d; font-size: 0.9rem; margin-bottom: 8px; }
    .form-control { border-radius: 8px; padding: 10px 15px; border: 1px solid #dee2e6; height: auto; min-height: 45px; }
    textarea[name="description"] { min-height: 150px !important; }
    textarea[name="specs"] { min-height: 200px !important; }
    .image-edit-box { 
        border: 2px solid #f1f5f9; 
        border-radius: 15px; 
        background: #ffffff; 
        cursor: pointer; 
        transition: all 0.3s; 
        height: 300px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
    }
    .image-edit-box:hover { border-color: #007bff; }
    .btn-update { background: #007bff; color: white; border-radius: 10px; padding: 12px 30px; font-weight: 700; }
</style>

<div class="content-wrapper bg-light">
    <div class="content-header p-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">
                        <i class="fas fa-tools text-primary mr-2"></i>Moderate Product Offer
                    </h1>
                    <p class="text-muted mb-0">Moderating post by: <span class="text-dark font-weight-bold">{{ $offer->supplier->company_name }}</span></p>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.supplier_content.index', ['supplier_id' => $offer->supplier_id]) }}" class="btn btn-outline-secondary px-4 shadow-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content px-4 pb-5">
        <div class="container-fluid">
            <form action="{{ route('admin.supplier_content.update', $offer->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- LEFT: Media & Status --}}
                    <div class="col-lg-4 mb-4">
                        <div class="card card-modern shadow-sm mb-4">
                            <div class="card-header bg-white pt-4 px-4 border-0">
                                <span class="section-title">Product Image</span>
                            </div>
                            <div class="card-body px-4">
                                <div class="image-edit-box mb-3 shadow-sm" onclick="document.getElementById('productImage').click();">
                                    <img id="previewImg" src="{{ $offer->product->image ? asset('storage/'.$offer->product->image) : asset('assets/dist/img/default-150x150.png') }}" style="width:100%; height:100%; object-fit:contain; padding: 15px;">
                                </div>
                                <input type="file" id="productImage" class="d-none" name="image" accept="image/*" onchange="previewImage(event)">
                                <p class="text-center text-muted small mt-2">Click image to replace</p>
                            </div>
                        </div>

                        <div class="card card-modern shadow-sm">
                            <div class="card-header bg-white pt-4 px-4 border-0">
                                <span class="section-title">Moderation Status</span>
                            </div>
                            <div class="card-body px-4 pt-0 pb-4">
                                <div class="form-group">
                                    <label>Content Visibility <span class="text-danger">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="available" {{ old('status', $offer->product->status) == 'available' ? 'selected' : '' }}>Visible (Available)</option>
                                        <option value="limited" {{ old('status', $offer->product->status) == 'limited' ? 'selected' : '' }}>Visible (Limited)</option>
                                        <option value="unavailable" {{ old('status', $offer->product->status) == 'unavailable' ? 'selected' : '' }}>Visible (Unavailable)</option>
                                        <option value="hidden" {{ old('status', $offer->product->status) == 'hidden' ? 'selected' : '' }} class="text-danger font-weight-bold">HIDDEN / BLOCKED</option>
                                    </select>
                                    <small class="text-muted mt-2 d-block">Setting status to 'Hidden' will remove this post from the marketplace immediately.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: Data --}}
                    <div class="col-lg-8 mb-4">
                        @if ($errors->any())
                            <div class="alert alert-danger shadow-sm mb-4 border-0">
                                <ul class="mb-0 list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="card card-modern shadow-sm mb-4">
                            <div class="card-header bg-white pt-4 px-4 border-0">
                                <span class="section-title">Edit Product Details</span>
                            </div>
                            <div class="card-body px-4 pt-0">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>Product Name <span class="text-danger">*</span></label>
                                        <input class="form-control" name="name" value="{{ old('name', $offer->product->name) }}" required>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label>Product Description</label>
                                        <textarea class="form-control" name="description">{{ old('description', $offer->product->description) }}</textarea>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label>Technical Specifications</label>
                                        <textarea class="form-control" name="specs">{{ old('specs', $offer->product->specs) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-modern shadow-sm mb-4 h-100">
                                    <div class="card-header bg-white pt-4 px-4 border-0">
                                        <span class="section-title">Categorization</span>
                                    </div>
                                    <div class="card-body px-4 pt-0">
                                        <div class="form-group">
                                            <label>Brand</label>
                                            <select class="form-control" name="brand_id" required>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ old('brand_id', $offer->product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select class="form-control" name="category_id" required>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $offer->product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-modern shadow-sm mb-4 h-100">
                                    <div class="card-header bg-white pt-4 px-4 border-0">
                                        <span class="section-title">Pricing & Inventory</span>
                                    </div>
                                    <div class="card-body px-4 pt-0">
                                        <div class="form-group">
                                            <label>Supplier Price ($)</label>
                                            <input type="number" class="form-control" name="price" step="0.01" value="{{ old('price', $offer->price) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Available Quantity</label>
                                            <input type="number" class="form-control" name="available_qty" value="{{ old('available_qty', $offer->available_qty) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right mt-4 pb-5">
                            <button type="submit" class="btn btn-update px-5 shadow-lg">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('previewImg');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
