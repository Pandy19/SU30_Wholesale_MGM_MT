@extends('backend.layouts.master')

@section('main-content')
<style>
    .card-modern { border: none; border-radius: 12px; transition: all 0.3s; }
    .card-modern:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    .section-title { font-size: 1.1rem; font-weight: 700; color: #2c3e50; border-bottom: 2px solid #f39c12; padding-bottom: 8px; display: inline-block; margin-bottom: 20px; }
    
    .form-group label { font-weight: 600; color: #5a6c7d; font-size: 0.9rem; margin-bottom: 8px; }
    .form-control { border-radius: 8px; padding: 10px 15px; border: 1px solid #dee2e6; transition: all 0.2s; height: auto; min-height: 45px; }
    select.form-control { height: 45px !important; padding-top: 8px !important; padding-bottom: 8px !important; }
    
    /* Increased heights for textareas */
    textarea[name="description"] { min-height: 180px !important; line-height: 1.6; }
    textarea[name="specs"] { min-height: 250px !important; line-height: 1.6; }
    
    .image-edit-box { 
        border: 2px solid #f1f5f9; 
        border-radius: 15px; 
        background: #ffffff; 
        cursor: pointer; 
        transition: all 0.3s; 
        position: relative; 
        overflow: hidden; 
        height: 350px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        box-shadow: inset 0 0 10px rgba(0,0,0,0.02);
    }
    .image-edit-box:hover { border-color: #f39c12; }
    .image-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.6); color: white; padding: 12px; text-align: center; opacity: 0; transition: 0.3s; backdrop-filter: blur(2px); }
    .image-edit-box:hover .image-overlay { opacity: 1; }
    
    .btn-update { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none; border-radius: 10px; padding: 12px 30px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s; }
    .btn-update:hover { background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); transform: scale(1.02); color: white; box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3); }
    
    .input-group-text { border-radius: 8px 0 0 8px; background: #f1f5f9; color: #64748b; font-weight: 600; border: 1px solid #dee2e6; min-width: 45px; justify-content: center; }
    .form-control-with-icon { border-radius: 0 8px 8px 0 !important; }
    .badge-status { border-radius: 20px; padding: 6px 18px; font-size: 0.8rem; text-transform: uppercase; font-weight: 700; }
</style>

<div class="content-wrapper bg-light">
    <div class="content-header p-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold" style="color: #1e293b;">
                        <i class="fas fa-edit text-warning mr-2"></i>Edit Product Offer
                    </h1>
                    <p class="text-muted mb-0">Modify details for <span class="text-dark font-weight-bold">{{ $offer->product_name }}</span></p>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('Supplier_Dashboard.index') }}" class="btn btn-outline-secondary px-4 shadow-sm" style="border-radius: 10px;">
                        <i class="fas fa-times mr-1"></i> Discard Changes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content px-4 pb-5">
        <div class="container-fluid">
            <form action="{{ route('Supplier_Dashboard.offer.update', $offer->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- LEFT: Media --}}
                    <div class="col-lg-4 mb-4">
                        <div class="card card-modern shadow-sm mb-4">
                            <div class="card-header bg-white pt-4 px-4 border-0">
                                <span class="section-title">Product Image</span>
                            </div>
                            <div class="card-body px-4">
                                <div class="image-edit-box mb-3 shadow-sm" onclick="document.getElementById('productImage').click();">
                                    <img id="previewImg" src="{{ $offer->image ? asset('storage/'.$offer->image) : 'https://via.placeholder.com/350?text=No+Image' }}" style="width:100%; height:100%; object-fit:contain; padding: 15px;">
                                    <div class="image-overlay">
                                        <i class="fas fa-camera mr-1"></i> Change Photo
                                    </div>
                                </div>
                                <input type="file" id="productImage" class="d-none" name="image" accept="image/*" onchange="previewImage(event)">
                                <p class="text-center text-muted small mt-2">Click the image area above to upload new</p>
                            </div>
                        </div>

                        <div class="card card-modern shadow-sm">
                            <div class="card-header bg-white pt-4 px-4 border-0">
                                <span class="section-title">Product Identity</span>
                            </div>
                            <div class="card-body px-4 pt-0 pb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted small">Unique SKU:</span>
                                    <span class="font-weight-bold badge badge-light px-3 py-2" style="border: 1px solid #e2e8f0;">{{ $offer->sku }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Current Visibility:</span>
                                    @if($offer->product_status == 'available')
                                        <span class="badge badge-success badge-status shadow-sm">Active</span>
                                    @elseif($offer->product_status == 'limited')
                                        <span class="badge badge-warning badge-status shadow-sm text-white">Limited</span>
                                    @else
                                        <span class="badge badge-danger badge-status shadow-sm">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: Data --}}
                    <div class="col-lg-8 mb-4">
                        @if ($errors->any())
                            <div class="alert alert-danger shadow-sm mb-4 border-0" style="border-radius: 12px;">
                                <ul class="mb-0 list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="card card-modern shadow-sm mb-4">
                            <div class="card-header bg-white pt-4 px-4 border-0">
                                <span class="section-title">General Information</span>
                            </div>
                            <div class="card-body px-4 pt-0">
                                <div class="row">
                                    <div class="col-md-8 form-group">
                                        <label>Product Name <span class="text-danger">*</span></label>
                                        <input class="form-control" name="name" value="{{ old('name', $offer->product_name) }}" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Availability <span class="text-danger">*</span></label>
                                        <select class="form-control" name="status" required>
                                            <option value="available" {{ old('status', $offer->product_status) == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="limited" {{ old('status', $offer->product_status) == 'limited' ? 'selected' : '' }}>Limited Stock</option>
                                            <option value="unavailable" {{ old('status', $offer->product_status) == 'unavailable' ? 'selected' : '' }}>Out of Stock</option>
                                        </select>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label>Product Description</label>
                                        <textarea class="form-control" name="description">{{ old('description', $offer->description) }}</textarea>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label>Technical Specifications</label>
                                        <textarea class="form-control" name="specs">{{ old('specs', $offer->specs) }}</textarea>
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
                                            <label>Brand Manufacturer</label>
                                            <select class="form-control" name="brand_id" required>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ old('brand_id', $offer->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Category Group</label>
                                            <select class="form-control" name="category_id" required>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $offer->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                            <label>Unit Cost Price ($)</label>
                                            <div class="input-group shadow-sm" style="border-radius: 8px;">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white font-weight-bold">$</span>
                                                </div>
                                                <input type="number" class="form-control form-control-with-icon" name="price" step="0.01" value="{{ old('price', $offer->price) }}" required style="border-left: none;">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Stock Quantity Available</label>
                                            <div class="input-group shadow-sm" style="border-radius: 8px;">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i class="fas fa-boxes text-muted"></i></span>
                                                </div>
                                                <input type="number" class="form-control form-control-with-icon" name="available_qty" value="{{ old('available_qty', $offer->available_qty) }}" required style="border-left: none;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right mt-4 pb-5">
                            <button type="submit" class="btn btn-update px-5 shadow-lg">
                                <i class="fas fa-save mr-2"></i> Update Product Offer
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
