@extends('backend.layouts.master')

@section('main-content')
<style>
    .card-modern { border: none; border-radius: 12px; transition: all 0.3s; }
    .card-modern:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    .section-title { font-size: 1.1rem; font-weight: 700; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 8px; display: inline-block; margin-bottom: 20px; }
    
    .form-group label { font-weight: 600; color: #5a6c7d; font-size: 0.9rem; margin-bottom: 8px; }
    .form-control { border-radius: 8px; padding: 10px 15px; border: 1px solid #dee2e6; transition: all 0.2s; height: auto; min-height: 45px; }
    select.form-control { height: 45px !important; padding-top: 8px !important; padding-bottom: 8px !important; }
    
    /* Increased heights for textareas */
    textarea[name="description"] { min-height: 180px !important; line-height: 1.6; }
    textarea[name="specs"] { min-height: 250px !important; line-height: 1.6; }
    
    /* Enhanced Image Upload Box */
    .image-upload-box { 
        border: 2px dashed #3498db; 
        border-radius: 15px; 
        background: #f0f7ff; 
        cursor: pointer; 
        transition: all 0.3s; 
        position: relative; 
        overflow: hidden; 
        height: 350px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        flex-direction: column; 
    }
    .image-upload-box:hover { border-color: #2ecc71; background: #ffffff; box-shadow: inset 0 0 10px rgba(52, 152, 219, 0.1); }
    .image-upload-box i { font-size: 4rem; color: #3498db; margin-bottom: 15px; transition: 0.3s; }
    .image-upload-box:hover i { transform: scale(1.1); color: #2ecc71; }
    .btn-select-img { background: #3498db; color: white; border-radius: 20px; padding: 5px 20px; font-size: 0.8rem; font-weight: 600; margin-top: 10px; border: none; }
    
    .btn-gradient { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); color: white; border: none; border-radius: 10px; padding: 12px 30px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s; }
    .btn-gradient:hover { background: linear-gradient(135deg, #27ae60 0%, #219150 100%); transform: scale(1.02); color: white; }
    .input-group-text { border-radius: 8px 0 0 8px; background: #f1f5f9; color: #64748b; font-weight: 600; border: 1px solid #dee2e6; min-width: 45px; justify-content: center; }
    .form-control-with-icon { border-radius: 0 8px 8px 0 !important; }
</style>

<div class="content-wrapper bg-light">
    <div class="content-header p-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold" style="color: #1e293b;">
                        <i class="fas fa-plus-circle text-primary mr-2"></i>Upload Product
                    </h1>
                    <p class="text-muted mb-0">List a new product offer to the marketplace</p>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('Supplier_Dashboard.index') }}" class="btn btn-outline-secondary px-4 shadow-sm" style="border-radius: 10px;">
                        <i class="fas fa-arrow-left mr-1"></i> Cancel & Return
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content px-4 pb-5">
        <div class="container-fluid">
            <form action="{{ route('Supplier_Dashboard.offer.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- LEFT: Media & Visuals --}}
                    <div class="col-lg-4 mb-4">
                        <div class="card card-modern shadow-sm h-100">
                            <div class="card-header bg-white pt-4 px-4 border-0">
                                <span class="section-title">Product Visuals</span>
                            </div>
                            <div class="card-body px-4">
                                <label class="mb-3">Product Main Image <span class="text-danger">*</span></label>
                                <div class="image-upload-box mb-3 shadow-sm" onclick="document.getElementById('productImage').click();">
                                    <img id="previewImg" style="width:100%; height:100%; object-fit:contain; display:none; padding: 10px;">
                                    <div id="uploadPlaceholder" class="text-center">
                                        <i class="fas fa-images"></i>
                                        <p class="mb-1 font-weight-bold" style="color: #2c3e50;">Click here to select image</p>
                                        <p class="small text-muted mb-2">or drag and drop file here</p>
                                        <span class="btn-select-img">Browse Files</span>
                                    </div>
                                </div>
                                <input type="file" id="productImage" class="d-none" name="image" accept="image/*" onchange="previewImage(event)" required>
                                
                                <div class="alert mt-4 border-0" style="border-radius: 12px; background: #fff9db; color: #856404; border-left: 4px solid #f1c40f !important;">
                                    <div class="d-flex">
                                        <i class="fas fa-lightbulb mt-1 mr-2"></i>
                                        <small>Tip: Use a clear, high-resolution image (800x800px) to attract more buyers.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: Data Entry --}}
                    <div class="col-lg-8 mb-4">
                        @if ($errors->any())
                            <div class="alert alert-danger shadow-sm mb-4 border-0" style="border-radius: 12px;">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li><i class="fas fa-exclamation-triangle mr-2"></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- BASIC INFO --}}
                        <div class="card card-modern shadow-sm mb-4">
                            <div class="card-header bg-white pt-4 px-4 border-0">
                                <span class="section-title">Basic Information</span>
                            </div>
                            <div class="card-body px-4 pt-0">
                                <div class="row">
                                    <div class="col-md-8 form-group">
                                        <label>Product Name <span class="text-danger">*</span></label>
                                        <input class="form-control" name="name" placeholder="e.g. iPhone 15 Pro Max" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Product Status <span class="text-danger">*</span></label>
                                        <select class="form-control" name="status" required>
                                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="limited" {{ old('status') == 'limited' ? 'selected' : '' }}>Limited Stock</option>
                                            <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Out of Stock</option>
                                        </select>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label>Product Description <span class="text-muted font-weight-normal">(More details help customers)</span></label>
                                        <textarea class="form-control" name="description" placeholder="Provide a comprehensive description of the product features and benefits...">{{ old('description') }}</textarea>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label>Technical Specifications</label>
                                        <textarea class="form-control" name="specs" placeholder="List technical data (e.g. Battery: 5000mAh, RAM: 8GB)...">{{ old('specs') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CLASSIFICATION & PRICING --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-modern shadow-sm mb-4 h-100">
                                    <div class="card-header bg-white pt-4 px-4 border-0">
                                        <span class="section-title">Categorization</span>
                                    </div>
                                    <div class="card-body px-4 pt-0">
                                        <div class="form-group">
                                            <label>Brand Manufacturer <span class="text-danger">*</span></label>
                                            <select class="form-control" name="brand_id" required>
                                                <option value="">Choose Brand...</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Category Group <span class="text-danger">*</span></label>
                                            <select class="form-control" name="category_id" required>
                                                <option value="">Choose Category...</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-modern shadow-sm mb-4 h-100">
                                    <div class="card-header bg-white pt-4 px-4 border-0">
                                        <span class="section-title">Pricing & Stock</span>
                                    </div>
                                    <div class="card-body px-4 pt-0">
                                        <div class="form-group">
                                            <label>Unit Cost Price <span class="text-danger">*</span></label>
                                            <div class="input-group shadow-sm" style="border-radius: 8px;">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white">$</span>
                                                </div>
                                                <input type="number" class="form-control form-control-with-icon" name="price" step="0.01" placeholder="0.00" value="{{ old('price') }}" required style="border-left: none;">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Initial Quantity <span class="text-danger">*</span></label>
                                            <div class="input-group shadow-sm" style="border-radius: 8px;">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white"><i class="fas fa-boxes text-muted"></i></span>
                                                </div>
                                                <input type="number" class="form-control form-control-with-icon" name="available_qty" placeholder="0" value="{{ old('available_qty') }}" required style="border-left: none;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="text-right mt-4 pb-5">
                            <button type="submit" class="btn btn-gradient px-5 shadow-lg">
                                <i class="fas fa-rocket mr-2"></i> Publish Product Offer
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
            var placeholder = document.getElementById('uploadPlaceholder');
            output.src = reader.result;
            output.style.display = 'block';
            placeholder.style.display = 'none';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
