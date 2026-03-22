<!-- ADD BRAND MODAL -->
<div class="modal fade shadow-lg" id="addBrandModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content border-0">
         <!-- HEADER -->
         <div class="modal-header bg-gradient-primary text-white py-3">
            <h5 class="modal-title font-weight-bold">
               <i class="fas fa-industry mr-2"></i>Create New Brand
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>

         <!-- BODY -->
         <div class="modal-body p-4">
            <form method="POST" action="{{ route('suppliers.brand.store') }}" enctype="multipart/form-data">
               @csrf

               {{-- BRAND LOGO (Modern Profile Style) --}}
               <div class="form-group text-center mb-4">
                  <label class="d-block font-weight-bold text-muted mb-3">Brand Identity Logo</label>
                  <div class="brand-photo-wrapper mx-auto shadow-sm border">
                     {{-- Preview image --}}
                     <img id="brandLogoPreview"
                           src="{{ asset('assets/dist/img/MMOLOGO1.png') }}"
                           alt="Brand Logo Preview"
                           class="brand-photo-preview"
                           style="object-fit: contain; padding: 10px;">

                     {{-- Hover overlay --}}
                     <div class="brand-photo-overlay bg-primary">
                           <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                           <span id="brandLogoText" class="font-weight-bold">Change Logo</span>
                     </div>

                     {{-- Real file input --}}
                     <input type="file" name="logo" id="brandLogoInput" class="brand-photo-input" accept="image/*">
                  </div>
                  @error('logo') <div class="text-danger small mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div> @enderror
                  <p class="text-muted small mt-2 mb-0">Recommended: Square PNG or JPG (Max 5MB)</p>
               </div>

               <div class="row">
                  <!-- BRAND NAME -->
                  <div class="col-md-12">
                     <div class="form-group mb-4">
                        <label class="font-weight-bold"><i class="fas fa-tag mr-1 text-primary"></i> Brand Name</label>
                        <input type="text" name="brand_name" class="form-control form-control-lg @error('brand_name') is-invalid @enderror"
                               placeholder="e.g. Apple, Samsung, Sony" value="{{ old('brand_name') }}" required>
                        @error('brand_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                     </div>
                  </div>

                  <!-- CATEGORY -->
                  <div class="col-md-6">
                     <div class="form-group mb-4">
                        <label class="font-weight-bold"><i class="fas fa-layer-group mr-1 text-primary"></i> Industry Category</label>
                        <select name="category_id" class="form-control select2 @error('category_id') is-invalid @enderror" required style="height: calc(2.25rem + 2px);">
                           <option value="">-- Choose Category --</option>
                           @foreach($categories as $category)
                              <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                 {{ $category->name }}
                              </option>
                           @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                     </div>
                  </div>

                  <!-- STATUS -->
                  <div class="col-md-6">
                     <div class="form-group mb-4">
                        <label class="font-weight-bold"><i class="fas fa-toggle-on mr-1 text-primary"></i> Brand Status</label>
                        <div class="d-flex align-items-center mt-1">
                           <div class="custom-control custom-radio mr-4">
                              <input class="custom-control-input" type="radio" id="brandStatusActive" name="status" value="active" {{ old('status','active') == 'active' ? 'checked' : '' }}>
                              <label for="brandStatusActive" class="custom-control-label text-success">Active</label>
                           </div>
                           <div class="custom-control custom-radio">
                              <input class="custom-control-input" type="radio" id="brandStatusInactive" name="status" value="inactive" {{ old('status') == 'inactive' ? 'checked' : '' }}>
                              <label for="brandStatusInactive" class="custom-control-label text-muted">Inactive</label>
                           </div>
                        </div>
                        @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                     </div>
                  </div>
               </div>

               <!-- FOOTER -->
               <div class="modal-footer px-0 pb-0 pt-3 border-top">
                  <button type="button" class="btn btn-light px-4 border text-uppercase font-weight-bold" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold">
                     <i class="fas fa-check-circle mr-1"></i>REGISTER BRAND
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<!-- ADD SUPPLIER MODAL -->
<div class="modal fade shadow-lg" id="addSupplierModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
      <div class="modal-content border-0">
         <div class="modal-header bg-gradient-success text-white py-3">
            <h5 class="modal-title font-weight-bold">
               <i class="fas fa-user-plus mr-2"></i>Register New Supplier
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>

         <div class="modal-body p-4">
            <form method="POST" action="{{ route('suppliers.supplier.store') }}" enctype="multipart/form-data">
               @csrf
               
               <div class="row">
                  {{-- LEFT COLUMN: Basic Info --}}
                  <div class="col-md-8">
                     <div class="card card-outline card-success shadow-none border">
                        <div class="card-header"><h3 class="card-title font-weight-bold text-muted small uppercase">Business Identity</h3></div>
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group mb-3">
                                    <label class="font-weight-bold">Company Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                       <div class="input-group-prepend"><span class="input-group-text bg-light"><i class="fas fa-building small"></i></span></div>
                                       <input type="text" name="company_name" class="form-control" placeholder="Legal Entity Name" value="{{ old('company_name') }}" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group mb-3">
                                    <label class="font-weight-bold">Contact Person</label>
                                    <div class="input-group">
                                       <div class="input-group-prepend"><span class="input-group-text bg-light"><i class="fas fa-user-tie small"></i></span></div>
                                       <input type="text" name="contact_person" class="form-control" placeholder="Full Name" value="{{ old('contact_person') }}">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group mb-3">
                                    <label class="font-weight-bold">Industry Category <span class="text-danger">*</span></label>
                                    <select id="supplierCategorySelect" class="form-control" required>
                                       <option value="">-- Choose Category --</option>
                                       @foreach($categories as $cat)
                                          <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group mb-3">
                                    <label class="font-weight-bold">Authorized Brand <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                       <div class="input-group-prepend"><span class="input-group-text bg-light text-success"><i class="fas fa-certificate small"></i></span></div>
                                       <select id="supplierBrandSelect" name="brand_id" class="form-control" required>
                                          <option value="">-- Choose Brand (Select Category First) --</option>
                                          @foreach($allBrands as $brand)
                                             <option value="{{ $brand->id }}" data-category="{{ $brand->category_id }}" style="display:none;">
                                                {{ $brand->name }}
                                             </option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div class="card card-outline card-success shadow-none border mt-3">
                        <div class="card-header"><h3 class="card-title font-weight-bold text-muted small uppercase">Login Credentials</h3></div>
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group mb-3">
                                    <label class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                       <div class="input-group-prepend"><span class="input-group-text bg-light"><i class="fas fa-envelope small"></i></span></div>
                                       <input type="email" name="email" class="form-control" placeholder="supplier@example.com" value="{{ old('email') }}" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group mb-3">
                                    <label class="font-weight-bold">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group mb-3">
                                    <label class="font-weight-bold">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                  {{-- RIGHT COLUMN: Terms & Docs --}}
                  <div class="col-md-4">
                     <div class="card card-outline card-success shadow-none border h-100">
                        <div class="card-header"><h3 class="card-title font-weight-bold text-muted small uppercase">Commercial Terms</h3></div>
                        <div class="card-body">
                           <div class="form-group mb-3">
                              <label class="font-weight-bold small">PAYMENT TERMS</label>
                              <select name="payment_term" class="form-control" required>
                                 <option value="Immediate">Immediate</option>
                                 <option value="Net 7 Days">Net 7 Days</option>
                                 <option value="Net 15 Days">Net 15 Days</option>
                                 <option value="Net 30 Days" selected>Net 30 Days</option>
                                 <option value="Net 60 Days">Net 60 Days</option>
                              </select>
                           </div>
                           
                           <div class="form-group mb-3">
                              <label class="font-weight-bold small">LEAD TIME (DAYS)</label>
                              <input type="number" name="lead_time_days" class="form-control" value="7" min="0">
                           </div>

                           <div class="form-group mb-3">
                              <label class="font-weight-bold small">INITIAL STATUS</label>
                              <select name="status" class="form-control" required>
                                 <option value="active" selected>Active</option>
                                 <option value="inactive">Inactive</option>
                              </select>
                           </div>

                           <hr>

                           <div class="form-group mb-0">
                              <label class="font-weight-bold small">BUSINESS LICENSE / ID CARD</label>
                              <div class="custom-file mb-2">
                                 <input type="file" name="document" class="custom-file-input" id="supplierDocInput">
                                 <label class="custom-file-label text-truncate" for="supplierDocInput" id="supplierDocLabel">Choose file...</label>
                              </div>
                              
                              {{-- Preview Container --}}
                              <div id="supplierDocPreviewContainer" class="border rounded p-2 text-center bg-light" style="display:none; position:relative;">
                                 <button type="button" class="close text-danger" style="position:absolute; right:5px; top:0;" onclick="removeSupplierDoc()">&times;</button>
                                 <img id="supplierDocImagePreview" src="" class="img-fluid rounded mb-2" style="max-height:100px; display:none;">
                                 <div id="supplierDocIcon" class="display-4 mb-2" style="display:none;"></div>
                                 <div class="small font-weight-bold text-truncate" id="supplierDocFileName"></div>
                                 <div class="small text-muted" id="supplierDocFileSize"></div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                  {{-- BOTTOM FULL WIDTH: Contact --}}
                  <div class="col-md-12 mt-3">
                     <div class="card card-outline card-success shadow-none border">
                        <div class="card-body py-3">
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group mb-0">
                                    <label class="small font-weight-bold">PHONE NUMBER</label>
                                    <input type="text" name="phone" class="form-control form-control-sm" placeholder="+123456789">
                                 </div>
                              </div>
                              <div class="col-md-8">
                                 <div class="form-group mb-0">
                                    <label class="small font-weight-bold">OFFICE ADDRESS</label>
                                    <input type="text" name="address" class="form-control form-control-sm" placeholder="Street, City, Country">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="modal-footer px-0 pb-0 pt-4 border-top">
                  <button type="button" class="btn btn-light px-4 border text-uppercase font-weight-bold" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-success px-5 shadow font-weight-bold">
                     <i class="fas fa-save mr-2"></i>CREATE SUPPLIER ACCOUNT
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // BRAND LOGO PREVIEW
    $('#brandLogoInput').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#brandLogoPreview').attr('src', e.target.result);
                $('#brandLogoText').text('Change Logo');
            }
            reader.readAsDataURL(file);
        }
    });

    // CATEGORY -> BRAND FILTERING
    $('#supplierCategorySelect').on('change', function() {
        const categoryId = $(this).val();
        const brandSelect = $('#supplierBrandSelect');
        const brandIcon = brandSelect.closest('.input-group').find('.input-group-text i');
        
        // Reset brand selection
        brandSelect.val('');
        
        if (categoryId) {
            // Show only brands belonging to this category
            let matchCount = 0;
            brandSelect.find('option').each(function() {
                const option = $(this);
                const brandCat = option.data('category');
                
                if (!brandCat) return; // Skip "Choose Brand" option
                
                if (brandCat == categoryId) {
                    option.show();
                    matchCount++;
                } else {
                    option.hide();
                }
            });
            
            // UI feedback
            if (matchCount > 0) {
                brandIcon.removeClass('text-success').addClass('text-primary');
                labelNote = ' (Found ' + matchCount + ' brands)';
            } else {
                brandIcon.removeClass('text-primary').addClass('text-danger');
            }
        } else {
            // If no category selected, hide all brands
            brandSelect.find('option').not(':first').hide();
            brandIcon.removeClass('text-primary').addClass('text-success');
        }
    });

    // SUPPLIER DOCUMENT PREVIEW
    $('#supplierDocInput').on('change', function() {
        const file = this.files[0];
        const label = $('#supplierDocLabel');
        const container = $('#supplierDocPreviewContainer');
        const imgPreview = $('#supplierDocImagePreview');
        const iconPreview = $('#supplierDocIcon');
        const fileName = $('#supplierDocFileName');
        const fileSize = $('#supplierDocFileSize');

        if (file) {
            label.text(file.name);
            fileName.text(file.name);
            fileSize.text((file.size / 1024 / 1024).toFixed(2) + ' MB');
            container.fadeIn();

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.attr('src', e.target.result).show();
                    iconPreview.hide();
                }
                reader.readAsDataURL(file);
            } else {
                imgPreview.hide();
                iconPreview.show();
                // Set icon based on type
                if (file.type === 'application/pdf') {
                    iconPreview.html('<i class="fas fa-file-pdf text-danger"></i>');
                } else {
                    iconPreview.html('<i class="fas fa-file-word text-primary"></i>');
                }
            }
        } else {
            removeSupplierDoc();
        }
    });
});

function removeSupplierDoc() {
    $('#supplierDocInput').val('');
    $('#supplierDocLabel').text('Choose professional document...');
    $('#supplierDocPreviewContainer').fadeOut();
}
</script>
@endpush


<!-- ADD CATEGORY MODAL -->
<div class="modal fade shadow-lg" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content border-0">
         <div class="modal-header bg-gradient-info text-white py-3">
            <h5 class="modal-title font-weight-bold">
               <i class="fas fa-tags mr-2"></i>Create Category
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>

         <div class="modal-body p-4">
            <form method="POST" action="{{ route('suppliers.category.store') }}">
               @csrf

               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted uppercase">Category Code</label>
                        <input type="text" name="category_code" class="form-control bg-light text-info font-weight-bold" value="{{ $nextCategoryCode ?? 'CAT-001' }}" readonly>
                     </div>
                  </div>

                  <div class="col-md-12">
                     <div class="form-group mb-3">
                        <label class="font-weight-bold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="category_name" class="form-control form-control-lg @error('category_name') is-invalid @enderror" 
                               placeholder="e.g. Smartphones, Laptops" value="{{ old('category_name') }}" required>
                        @error('category_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                     </div>
                  </div>

                  <div class="col-md-12">
                     <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted">Short Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Briefly describe what belongs in this category...">{{ old('description') }}</textarea>
                     </div>
                  </div>

                  <div class="col-md-12">
                     <div class="form-group mb-4">
                        <label class="font-weight-bold">Initial Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                           <option value="active" {{ old('status','active')=='active' ? 'selected' : '' }}>Active</option>
                           <option value="inactive" {{ old('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                     </div>
                  </div>
               </div>

               <!-- FOOTER -->
               <div class="modal-footer px-0 pb-0 pt-3 border-top">
                  <button type="button" class="btn btn-light px-4 border" data-dismiss="modal">CANCEL</button>
                  <button type="submit" class="btn btn-info px-5 shadow-sm font-weight-bold">
                     <i class="fas fa-plus-circle mr-1"></i>SAVE CATEGORY
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
