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
                        <option value="{{ $category->id }}"
                           {{ old('category_id') == $category->id ? 'selected' : '' }}>
                           {{ $category->name }}
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
                        <select class="form-control border-primary @error('category_id') is-invalid @enderror" 
                                name="category_id" 
                                id="supplierCategorySelect"
                                required>
                           <option value="">-- Choose Category --</option>
                           @foreach($categories as $cat)
                              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                 {{ $cat->name }}
                              </option>
                           @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="font-weight-bold text-primary">Select Brand</label>
                        <select class="form-control border-primary @error('brand_id') is-invalid @enderror" 
                                name="brand_id" 
                                id="supplierBrandSelect"
                                required>
                           <option value="">-- Choose Brand (Select Category First) --</option>
                           @foreach($allBrands as $b)
                              <option value="{{ $b->id }}" 
                                      data-category="{{ $b->category_id }}"
                                      {{ old('brand_id') == $b->id ? 'selected' : '' }}
                                      style="display:none;">
                                 {{ $b->name }}
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
