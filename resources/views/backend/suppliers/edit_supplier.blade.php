@foreach($brands as $brand)
    @foreach($brand->suppliers as $supplier)
        @php
            $editModalId = 'editSupplierModal' . $supplier->id;
            $deleteModalId = 'deleteSupplierModal' . $supplier->id;
        @endphp

        {{-- ================= EDIT SUPPLIER MODAL ================= --}}
        <div class="modal fade shadow-lg" id="{{ $editModalId }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header bg-gradient-warning text-dark py-3">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-edit mr-2"></i>Edit Supplier Profile: {{ $supplier->company_name }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-4">
                        <form method="POST" action="{{ route('suppliers.supplier.update', $supplier->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold text-muted uppercase">Supplier Code</label>
                                        <input type="text" class="form-control bg-light font-weight-bold text-primary" name="code" value="{{ $supplier->code }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">COMPANY NAME <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company_name" value="{{ old('company_name', $supplier->company_name) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">CONTACT PERSON</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text bg-light"><i class="fas fa-user small"></i></span></div>
                                            <input type="text" class="form-control" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">PHONE NUMBER</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text bg-light"><i class="fas fa-phone small"></i></span></div>
                                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $supplier->phone) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">EMAIL ADDRESS</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text bg-light"><i class="fas fa-envelope small"></i></span></div>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $supplier->email) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">OFFICE ADDRESS</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text bg-light"><i class="fas fa-map-marker-alt small"></i></span></div>
                                            <input type="text" class="form-control" name="address" value="{{ old('address', $supplier->address) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">PAYMENT TERM</label>
                                        @php
                                            $terms = ['Immediate','Net 7 Days','Net 15 Days','Net 30 Days','Net 60 Days'];
                                            $selected = old('payment_term', $supplier->payment_term);
                                        @endphp
                                        <select class="form-control" name="payment_term" required>
                                            @foreach($terms as $term)
                                                <option value="{{ $term }}" {{ $selected === $term ? 'selected' : '' }}>{{ $term }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">LEAD TIME (DAYS)</label>
                                        <input type="number" class="form-control" name="lead_time_days" value="{{ old('lead_time_days', $supplier->lead_time_days) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">OPERATIONAL STATUS</label>
                                        <select class="form-control" name="status" required>
                                            <option value="active" {{ $supplier->status === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $supplier->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- DOCUMENT UPDATE --}}
                                <div class="col-md-12">
                                    <div class="form-group mt-2">
                                        <label class="font-weight-bold text-dark"><i class="fas fa-file-contract mr-2 text-primary"></i>Update Business License / ID</label>
                                        <div class="custom-file shadow-sm">
                                            <input type="file" name="document" class="custom-file-input" id="docEdit-{{ $supplier->id }}" 
                                                   onchange="previewSupplierDocEdit('{{ $supplier->id }}', this)">
                                            <label class="custom-file-label" for="docEdit-{{ $supplier->id }}">Replace current document...</label>
                                        </div>

                                        {{-- Current File Preview --}}
                                        <div class="mt-3 d-flex align-items-center p-2 border rounded bg-light">
                                            @if($supplier->document)
                                                <div class="mr-3 text-info"><i class="fas fa-file-alt fa-2x"></i></div>
                                                <div>
                                                    <div class="small font-weight-bold">Current Document:</div>
                                                    <a href="{{ asset('storage/' . $supplier->document) }}" target="_blank" class="small text-primary">
                                                        <i class="fas fa-external-link-alt mr-1"></i>View File
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-muted small italic"><i class="fas fa-info-circle mr-1"></i>No document uploaded yet.</div>
                                            @endif
                                        </div>

                                        {{-- New File Preview (Hidden initially) --}}
                                        <div class="mt-2" id="editDocPreviewWrap-{{ $supplier->id }}" style="display:none;">
                                            <div class="alert alert-success py-1 px-2 small d-inline-block">
                                                <i class="fas fa-check-circle mr-1"></i> New file selected: <span id="editDocName-{{ $supplier->id }}"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer px-0 pb-0 pt-4 border-top">
                                <button type="button" class="btn btn-light px-4 border" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning px-5 shadow-sm font-weight-bold">
                                    <i class="fas fa-save mr-2"></i>UPDATE SUPPLIER
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= DELETE SUPPLIER MODAL ================= --}}
        <div class="modal fade" id="{{ $deleteModalId }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Confirm Removal
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center p-5">
                        <i class="fas fa-user-minus fa-4x text-danger mb-4"></i>
                        <h5>Delete Supplier Account?</h5>
                        <p class="text-muted">You are about to permanently delete <strong>{{ $supplier->company_name }}</strong> from the system.</p>
                        <div class="alert alert-danger py-2 small mt-3">
                            <i class="fas fa-info-circle mr-1"></i> This action cannot be reversed.
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-light px-4 border" data-dismiss="modal">Wait, Cancel</button>
                        <form method="POST" action="{{ route('suppliers.supplier.delete', $supplier->id) }}" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4 shadow">Yes, Delete Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

<script>
function previewSupplierDocEdit(id, input) {
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        $(`#editDocName-${id}`).text(fileName);
        $(`#editDocPreviewWrap-${id}`).fadeIn();
        $(input).next('.custom-file-label').text(fileName);
    }
}
</script>
