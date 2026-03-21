<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal{{ $customer->id }}" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel{{ $customer->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="editCustomerModalLabel{{ $customer->id }}">
                    <i class="fas fa-user-edit mr-2"></i> EDIT CUSTOMER: {{ $customer->customer_code }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 py-4">
                    <!-- Profile Picture Upload -->
                    <div class="form-group text-center mb-4">
                        <label class="d-block text-uppercase small font-weight-bold text-muted mb-2">Update Photo</label>
                        <div class="brand-photo-wrapper mx-auto shadow-sm">
                            {{-- Preview image --}}
                            <img id="preview_img_edit{{ $customer->id }}"
                                 src="{{ $customer->profile_picture ? asset($customer->profile_picture) : asset('assets/dist/img/MMOLOGO1.png') }}"
                                 alt="Profile Preview"
                                 class="brand-photo-preview">

                            {{-- Hover overlay --}}
                            <div class="brand-photo-overlay">
                                <i class="fas fa-camera mr-1"></i>
                                <span id="photoTextEdit{{ $customer->id }}">Change</span>
                            </div>

                            {{-- Real file input --}}
                            <input type="file"
                                   name="profile_picture"
                                   id="profile_picture_input_edit{{ $customer->id }}"
                                   class="brand-photo-input"
                                   onchange="previewEditImage(this, '{{ $customer->id }}')"
                                   accept="image/*">
                        </div>
                        <small class="text-muted d-block mt-2">JPG/PNG (max 2MB)</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-uppercase small font-weight-bold text-muted">Customer Type</label>
                                <select name="type" id="type_select_edit{{ $customer->id }}" class="form-control custom-select-lg border-primary shadow-sm" onchange="toggleEditFields('{{ $customer->id }}')" required>
                                    <option value="B2C" {{ $customer->type == 'B2C' ? 'selected' : '' }}>Retail (B2C)</option>
                                    <option value="B2B" {{ $customer->type == 'B2B' ? 'selected' : '' }}>Wholesale (B2B)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="label_name_edit{{ $customer->id }}" class="text-uppercase small font-weight-bold text-muted" for="name_edit{{ $customer->id }}">
                                    {{ $customer->type == 'B2B' ? 'Company Name' : 'Customer Name' }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-user text-primary"></i></span>
                                    </div>
                                    <input type="text" name="name" id="name_edit{{ $customer->id }}" class="form-control border-left-0 shadow-sm" value="{{ $customer->name }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- B2B Specific Fields -->
                    <div id="b2b_fields_edit{{ $customer->id }}" style="{{ $customer->type == 'B2B' ? 'display: block;' : 'display: none;' }}">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-uppercase small font-weight-bold text-muted" for="contact_person_edit{{ $customer->id }}">Contact Person</label>
                                    <input type="text" name="contact_person" id="contact_person_edit{{ $customer->id }}" class="form-control shadow-sm" value="{{ $customer->contact_person }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-uppercase small font-weight-bold text-muted" for="tax_number_edit{{ $customer->id }}">Tax ID / VAT Number</label>
                                    <input type="text" name="tax_number" id="tax_number_edit{{ $customer->id }}" class="form-control shadow-sm" value="{{ $customer->tax_number }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-uppercase small font-weight-bold text-muted" for="phone_edit{{ $customer->id }}">Phone Number</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-phone text-primary"></i></span>
                                    </div>
                                    <input type="text" name="phone" id="phone_edit{{ $customer->id }}" class="form-control border-left-0 shadow-sm" value="{{ $customer->phone }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-uppercase small font-weight-bold text-muted" for="email_edit{{ $customer->id }}">Email Address</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-envelope text-primary"></i></span>
                                    </div>
                                    <input type="email" name="email" id="email_edit{{ $customer->id }}" class="form-control border-left-0 shadow-sm" value="{{ $customer->email }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-uppercase small font-weight-bold text-muted" for="address_edit{{ $customer->id }}">Primary Address</label>
                        <textarea name="address" id="address_edit{{ $customer->id }}" class="form-control shadow-sm" rows="2">{{ $customer->address }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-uppercase small font-weight-bold text-muted" for="credit_limit_edit{{ $customer->id }}">Credit Limit ($)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-wallet text-primary"></i></span>
                                    </div>
                                    <input type="number" step="0.01" name="credit_limit" id="credit_limit_edit{{ $customer->id }}" class="form-control border-left-0 shadow-sm" value="{{ $customer->credit_limit }}">
                                </div>
                            </div>
                        </div>
                        <div id="payment_terms_col_edit{{ $customer->id }}" class="col-md-4" style="{{ $customer->type == 'B2B' ? 'display: block;' : 'display: none;' }}">
                            <div class="form-group">
                                <label class="text-uppercase small font-weight-bold text-muted" for="payment_terms_edit{{ $customer->id }}">Payment Terms</label>
                                <select name="payment_terms" id="payment_terms_edit{{ $customer->id }}" class="form-control shadow-sm">
                                    <option value="" {{ empty($customer->payment_terms) ? 'selected' : '' }}>Select Terms</option>
                                    <option value="Net 15" {{ $customer->payment_terms == 'Net 15' ? 'selected' : '' }}>Net 15 (15 Days)</option>
                                    <option value="Net 30" {{ $customer->payment_terms == 'Net 30' ? 'selected' : '' }}>Net 30 (30 Days)</option>
                                    <option value="Net 60" {{ $customer->payment_terms == 'Net 60' ? 'selected' : '' }}>Net 60 (60 Days)</option>
                                    <option value="Due on Receipt" {{ $customer->payment_terms == 'Due on Receipt' ? 'selected' : '' }}>Due on Receipt</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-uppercase small font-weight-bold text-muted" for="status_edit{{ $customer->id }}">Account Status</label>
                                <select name="status" id="status_edit{{ $customer->id }}" class="form-control shadow-sm font-weight-bold" required>
                                    <option value="active" {{ $customer->status == 'active' ? 'selected' : '' }} class="text-success font-weight-bold">ACTIVE</option>
                                    <option value="on_hold" {{ $customer->status == 'on_hold' ? 'selected' : '' }} class="text-warning font-weight-bold">ON HOLD</option>
                                    <option value="blacklisted" {{ $customer->status == 'blacklisted' ? 'selected' : '' }} class="text-danger font-weight-bold">BLACKLISTED</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 border-top">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> CLOSE
                    </button>
                    <button type="submit" class="btn btn-warning px-5 py-2 font-weight-bold shadow-sm text-white">
                        <i class="fas fa-save mr-1"></i> UPDATE CUSTOMER
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewEditImage(input, id) {
    const preview = document.getElementById('preview_img_edit' + id);
    const photoText = document.getElementById('photoTextEdit' + id);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            if (photoText) photoText.textContent = 'Change';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleEditFields(id) {
    const typeSelect = document.getElementById('type_select_edit' + id);
    const b2bFields = document.getElementById('b2b_fields_edit' + id);
    const paymentTermsCol = document.getElementById('payment_terms_col_edit' + id);
    const labelName = document.getElementById('label_name_edit' + id);
    const inputName = document.getElementById('name_edit' + id);

    if (typeSelect.value === 'B2B') {
        b2bFields.style.display = 'block';
        paymentTermsCol.style.display = 'block';
        labelName.innerHTML = 'Company Name <span class="text-danger">*</span>';
        inputName.placeholder = 'Enter legal company name';
    } else {
        b2bFields.style.display = 'none';
        paymentTermsCol.style.display = 'none';
        labelName.innerHTML = 'Customer Name <span class="text-danger">*</span>';
        inputName.placeholder = 'Enter customer full name';
    }
}
</script>
