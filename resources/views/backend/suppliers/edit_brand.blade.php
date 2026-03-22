@foreach($brands as $brand)
<div class="modal fade shadow-lg" id="editBrandModal{{ $brand->id }}" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content border-0">
         <!-- HEADER -->
         <div class="modal-header bg-gradient-warning text-dark py-3">
            <h5 class="modal-title font-weight-bold">
               <i class="fas fa-edit mr-2"></i>Edit Brand: {{ $brand->name }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>

         <!-- BODY -->
         <div class="modal-body p-4">
            <form method="POST" action="{{ route('suppliers.brand.update', $brand->id) }}" enctype="multipart/form-data">
               @csrf
               @method('PUT')

               {{-- BRAND LOGO --}}
               <div class="form-group text-center mb-4">
                  <label class="d-block font-weight-bold text-muted mb-3">Brand Identity Logo</label>
                  <div class="brand-photo-wrapper mx-auto shadow-sm border">
                     {{-- Preview image --}}
                     <img id="brandLogoPreview{{ $brand->id }}"
                           src="{{ $brand->logo ? asset('storage/' . $brand->logo) : asset('assets/dist/img/MMOLOGO1.png') }}"
                           alt="Brand Logo Preview"
                           class="brand-photo-preview"
                           style="object-fit: contain; padding: 10px;">

                     {{-- Hover overlay --}}
                     <div class="brand-photo-overlay bg-warning">
                           <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-dark"></i>
                           <span class="font-weight-bold text-dark">Update Logo</span>
                     </div>

                     {{-- Real file input --}}
                     <input type="file" name="logo" class="brand-photo-input" accept="image/*" 
                            onchange="previewBrandLogoEdit(this, '{{ $brand->id }}')">
                  </div>
                  <p class="text-muted small mt-2 mb-0">Leave empty to keep current logo</p>
               </div>

               <div class="row">
                  <!-- BRAND NAME -->
                  <div class="col-md-12">
                     <div class="form-group mb-4">
                        <label class="font-weight-bold"><i class="fas fa-tag mr-1 text-warning"></i> Brand Name</label>
                        <input type="text" name="brand_name" class="form-control form-control-lg"
                               placeholder="e.g. Apple, Samsung" value="{{ old('brand_name', $brand->name) }}" required>
                     </div>
                  </div>

                  <!-- CATEGORY -->
                  <div class="col-md-6">
                     <div class="form-group mb-4">
                        <label class="font-weight-bold"><i class="fas fa-layer-group mr-1 text-warning"></i> Industry Category</label>
                        <select name="category_id" class="form-control" required style="height: calc(2.25rem + 2px);">
                           @foreach($categories as $category)
                              <option value="{{ $category->id }}" {{ $brand->category_id == $category->id ? 'selected' : '' }}>
                                 {{ $category->name }}
                              </option>
                           @endforeach
                        </select>
                     </div>
                  </div>

                  <!-- STATUS -->
                  <div class="col-md-6">
                     <div class="form-group mb-4">
                        <label class="font-weight-bold"><i class="fas fa-toggle-on mr-1 text-warning"></i> Brand Status</label>
                        <div class="d-flex align-items-center mt-1">
                           <div class="custom-control custom-radio mr-4">
                              <input class="custom-control-input" type="radio" id="brandStatusActive{{ $brand->id }}" name="status" value="active" {{ $brand->status == 'active' ? 'checked' : '' }}>
                              <label for="brandStatusActive{{ $brand->id }}" class="custom-control-label text-success">Active</label>
                           </div>
                           <div class="custom-control custom-radio">
                              <input class="custom-control-input" type="radio" id="brandStatusInactive{{ $brand->id }}" name="status" value="inactive" {{ $brand->status == 'inactive' ? 'checked' : '' }}>
                              <label for="brandStatusInactive{{ $brand->id }}" class="custom-control-label text-muted">Inactive</label>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <!-- FOOTER -->
               <div class="modal-footer px-0 pb-0 pt-3 border-top">
                  <button type="button" class="btn btn-light px-4 border text-uppercase font-weight-bold" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-warning px-5 shadow-sm font-weight-bold">
                     <i class="fas fa-save mr-1"></i>UPDATE BRAND
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endforeach

<script>
function previewBrandLogoEdit(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#brandLogoPreview' + id).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
