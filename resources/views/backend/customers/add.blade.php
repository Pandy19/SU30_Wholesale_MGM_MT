<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="addCustomerModalLabel">
                    <i class="fas fa-user-plus mr-2"></i> NEW CUSTOMER REGISTRATION
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 py-4">
                    <!-- Profile Picture Upload (Supplier Style) -->
                    <div class="form-group text-center mb-4">
                        <label class="d-block text-uppercase small font-weight-bold text-muted mb-2">Customer Photo</label>
                        <div class="brand-photo-wrapper mx-auto shadow-sm">
                            {{-- Preview image --}}
                            <img id="preview_img"
                                 src="{{ asset('assets/dist/img/MMOLOGO1.png') }}"
                                 alt="Profile Preview"
                                 class="brand-photo-preview">

                            {{-- Hover overlay --}}
                            <div class="brand-photo-overlay">
                                <i class="fas fa-camera mr-1"></i>
                                <span id="photoText">Upload</span>
                            </div>

                            {{-- Real file input --}}
                            <input type="file"
                                   name="profile_picture"
                                   id="profile_picture_input"
                                   class="brand-photo-input"
                                   accept="image/*">
                        </div>
                        <small class="text-muted d-block mt-2">Click to upload JPG/PNG (max 2MB)</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-uppercase small font-weight-bold text-muted" for="type_select">Customer Type</label>
                                <select name="type" id="type_select" class="form-control custom-select-lg border-primary shadow-sm" required>
                                    <option value="B2C">Retail (B2C)</option>
                                    <option value="B2B">Wholesale (B2B)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="label_name" class="text-uppercase small font-weight-bold text-muted" for="name">Customer Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-user text-primary"></i></span>
                                    </div>
                                    <input type="text" name="name" id="name" class="form-control border-left-0 shadow-sm" placeholder="Enter full name" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- B2B Specific Fields -->
                    <div id="b2b_fields" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-uppercase small font-weight-bold text-muted" for="contact_person">Contact Person</label>
                                    <input type="text" name="contact_person" id="contact_person" class="form-control shadow-sm" placeholder="Primary contact name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-uppercase small font-weight-bold text-muted" for="tax_number">Tax ID / VAT Number</label>
                                    <input type="text" name="tax_number" id="tax_number" class="form-control shadow-sm" placeholder="Business tax registration">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-uppercase small font-weight-bold text-muted" for="phone">Phone Number</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-phone text-primary"></i></span>
                                    </div>
                                    <input type="text" name="phone" id="phone" class="form-control border-left-0 shadow-sm" placeholder="Contact number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-uppercase small font-weight-bold text-muted" for="email">Email Address</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-envelope text-primary"></i></span>
                                    </div>
                                    <input type="email" name="email" id="email" class="form-control border-left-0 shadow-sm" placeholder="Email for notifications">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-uppercase small font-weight-bold text-muted" for="address">Primary Address</label>
                        <textarea name="address" id="address" class="form-control shadow-sm" rows="2" placeholder="Street, Building, City, etc."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-uppercase small font-weight-bold text-muted" for="credit_limit">Credit Limit ($)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-wallet text-primary"></i></span>
                                    </div>
                                    <input type="number" step="0.01" name="credit_limit" id="credit_limit" class="form-control border-left-0 shadow-sm" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div id="payment_terms_col" class="col-md-4" style="display: none;">
                            <div class="form-group">
                                <label class="text-uppercase small font-weight-bold text-muted" for="payment_terms">Payment Terms</label>
                                <select name="payment_terms" id="payment_terms" class="form-control shadow-sm">
                                    <option value="">Select Terms</option>
                                    <option value="Net 15">Net 15 (15 Days)</option>
                                    <option value="Net 30">Net 30 (30 Days)</option>
                                    <option value="Net 60">Net 60 (60 Days)</option>
                                    <option value="Due on Receipt">Due on Receipt</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-uppercase small font-weight-bold text-muted" for="status">Account Status</label>
                                <select name="status" id="status" class="form-control shadow-sm font-weight-bold" required>
                                    <option value="active" class="text-success font-weight-bold">ACTIVE</option>
                                    <option value="on_hold" class="text-warning font-weight-bold">ON HOLD</option>
                                    <option value="blacklisted" class="text-danger font-weight-bold">BLACKLISTED</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 border-top">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> CLOSE
                    </button>
                    <button type="submit" class="btn btn-primary px-5 py-2 font-weight-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> REGISTER CUSTOMER
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Custom Select Larger for more prominence */
.custom-select-lg {
    height: calc(1.5em + 1rem + 2px);
    padding: .5rem 1rem;
    font-size: 1.1rem;
}
.input-group-text {
    border-color: #ced4da;
}
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image Preview Functionality
    const profileInput = document.getElementById('profile_picture_input');
    const previewImg = document.getElementById('preview_img');
    const photoText = document.getElementById('photoText');

    profileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                if (photoText) photoText.textContent = 'Change';
            }
            reader.readAsDataURL(file);
        }
    });

    // Type Toggle Functionality
    const typeSelect = document.getElementById('type_select');
    const b2bFields = document.getElementById('b2b_fields');
    const paymentTermsCol = document.getElementById('payment_terms_col');
    const labelName = document.getElementById('label_name');
    const inputName = document.getElementById('name');

    function toggleFields() {
        if (typeSelect.value === 'B2B') {
            b2bFields.style.display = 'block';
            paymentTermsCol.style.display = 'block';
            paymentTermsCol.classList.add('col-md-4');
            labelName.innerHTML = 'Company Name <span class="text-danger">*</span>';
            inputName.placeholder = 'Enter legal company name';
        } else {
            b2bFields.style.display = 'none';
            paymentTermsCol.style.display = 'none';
            labelName.innerHTML = 'Customer Name <span class="text-danger">*</span>';
            inputName.placeholder = 'Enter customer full name';
        }
    }

    typeSelect.addEventListener('change', toggleFields);
    // Initialize on load
    toggleFields();
});
</script>
