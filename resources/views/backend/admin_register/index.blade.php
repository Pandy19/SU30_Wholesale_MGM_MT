@extends('backend.layouts.auth')

@section('title', 'Register | Wholesale MGM')

@section('content')

<div class="container-fluid">
<div class="row justify-content-center align-items-center" style="min-height:95vh;">

    <div class="col-lg-10 col-md-12">

        <div class="card shadow-lg">
            <div class="row no-gutters">

                <!-- LEFT SIDE : BRAND -->
                <div class="col-md-4 bg-primary text-white d-flex align-items-center justify-content-center">
                    <div class="text-center p-4">
                        <img src="{{ asset('assets/dist/img/MMOLOGO.png') }}"
                             alt="Wholesale MGM Logo"
                             style="width:200px; max-width:100%; height:auto;">
                        <h3 class="mt-3 font-weight-bold">Wholesale MGM</h3>
                        <p class="mb-0">Management System</p>
                    </div>
                </div>

                <!-- RIGHT SIDE : REGISTER FORM -->
                <div class="col-md-8">
                    <div class="card-body p-4">

                        <h4 class="mb-1 font-weight-bold">Create Backend Account</h4>
                        <p class="text-muted mb-3">
                            Register a new user for the wholesale system
                        </p>

                        <form method="POST" action="{{ route('admin_register.store') }}" enctype="multipart/form-data">
                            @csrf

                            @if($errors->any())
                                <div class="alert alert-danger py-2 small mb-3">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Please correct the errors below and <strong>re-select your files</strong> if they are not shown.
                                </div>
                            @endif

                            <!-- AVATAR -->
                            <div class="form-group text-center mb-3">
                                <label class="d-block font-weight-bold mb-2">
                                    Profile Avatar
                                </label>

                                <div class="avatar-wrapper mx-auto">
                                    <label for="avatarInput" class="avatar-label">
                                        <img
                                            src="{{ old('avatar_base64') ? old('avatar_base64') : asset('assets/dist/img/MMOLOGO.png') }}"
                                            id="avatarPreview"
                                            alt="Avatar">
                                    </label>

                                    <input type="file"
                                           id="avatarInput"
                                           name="avatar"
                                           accept="image/*"
                                           hidden>
                                    {{-- HIDDEN BASE64 --}}
                                    <input type="hidden" name="avatar_base64" id="avatarBase64" value="{{ old('avatar_base64') }}">
                                </div>

                                <small class="text-muted d-block mt-2">
                                    Click the image to upload (JPG / PNG)
                                </small>
                            </div>

                            <!-- ROLE -->
                            <div class="form-group">
                                <label>Register As</label>
                                <select name="role_id"
                                        id="roleSelect"
                                        class="form-control @error('role_id') is-invalid @enderror"
                                        required>
                                    <option value="">-- Select Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" 
                                                data-slug="{{ $role->slug }}"
                                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <hr class="my-3">

                            <!-- BASIC INFO -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Full Name</label>
                                    <input type="text"
                                           name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Enter full name"
                                           value="{{ old('name') }}"
                                           required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="email"
                                           name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="Enter email"
                                           value="{{ old('email') }}"
                                           required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Password</label>
                                    <input type="password"
                                           name="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Password"
                                           required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Confirm Password</label>
                                    <input type="password"
                                           name="password_confirmation"
                                           class="form-control"
                                           placeholder="Confirm password"
                                           required>
                                </div>
                            </div>

                            @php
                                $isSupplier = false;
                                if(old('role_id')) {
                                    $selectedRole = $roles->firstWhere('id', old('role_id'));
                                    if($selectedRole && $selectedRole->slug === 'supplier') $isSupplier = true;
                                }
                            @endphp

                            <!-- SUPPLIER VERIFICATION -->
                            <div id="supplierFields" style="display: {{ $isSupplier ? 'block' : 'none' }};">
                                <hr class="my-3">

                                <h6 class="text-warning font-weight-bold mb-2">
                                    Supplier Verification
                                </h6>

                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text"
                                           name="company_name"
                                           class="form-control"
                                           placeholder="Registered company name"
                                           value="{{ old('company_name') }}">
                                </div>

                                <div class="form-group">
                                    <label>Company ID / Business License</label>
                                    <input type="text"
                                           name="company_id"
                                           class="form-control"
                                           placeholder="Company ID number"
                                           value="{{ old('company_id') }}">
                                </div>

                                <div class="form-group">
                                    <label>Upload ID Card / License</label>
                                    <div class="mb-2" id="licensePreviewContainer">
                                        @if(old('license_base64'))
                                            @if(Str::startsWith(old('license_base64'), 'data:image'))
                                                <img id="licensePreview" src="{{ old('license_base64') }}" class="img-thumbnail" style="max-height: 100px;">
                                            @else
                                                <div id="licensePDFPreview" class="alert alert-light border small">
                                                    <i class="fas fa-file-pdf text-danger mr-1"></i> PDF Document Attached
                                                </div>
                                            @endif
                                        @else
                                            <img id="licensePreview" src="" class="img-thumbnail" style="max-height: 100px; display: none;">
                                            <div id="licensePDFPreview" class="alert alert-light border small" style="display: none;">
                                                <i class="fas fa-file-pdf text-danger mr-1"></i> PDF Document Attached
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file" name="license_doc" id="licenseInput" class="form-control-file" accept="image/*,application/pdf">
                                    {{-- HIDDEN BASE64 --}}
                                    <input type="hidden" name="license_base64" id="licenseBase64" value="{{ old('license_base64') }}">
                                    @if(old('license_base64'))
                                        <div class="mt-1 small text-success" id="licenseStatus"><i class="fas fa-paperclip mr-1"></i> Document re-attached.</div>
                                    @endif
                                    @error('license_doc') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="alert alert-warning py-2">
                                    <i class="fas fa-clock mr-1"></i>
                                    Supplier accounts require
                                    <strong>admin approval</strong>
                                    before activation.
                                </div>
                            </div>

                            <!-- REGISTER BUTTON -->
                            <button type="submit"
                                    class="btn btn-primary btn-block btn-lg mt-3">
                                Register Account
                            </button>

                            <!-- BACK TO LOGIN -->
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    Already have an account?
                                    <a href="{{ url('/admin_login') }}"
                                       class="font-weight-bold">
                                        Sign in
                                    </a>
                                </small>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

        <div class="text-center mt-2 text-muted">
            <small>&copy; {{ date('Y') }} Wholesale MGM</small>
        </div>

    </div>

</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('roleSelect');
    const supplierFields = document.getElementById('supplierFields');
    
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarBase64 = document.getElementById('avatarBase64');

    const licenseInput = document.getElementById('licenseInput');
    const licensePreview = document.getElementById('licensePreview');
    const licensePDFPreview = document.getElementById('licensePDFPreview');
    const licenseBase64 = document.getElementById('licenseBase64');
    const licenseStatus = document.getElementById('licenseStatus');

    // Toggle Supplier Fields
    roleSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const slug = selectedOption.getAttribute('data-slug');
        
        if (slug === 'supplier') {
            supplierFields.style.display = 'block';
        } else {
            supplierFields.style.display = 'none';
        }
    });

    // Avatar Preview + Base64 Save
    avatarInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
                avatarBase64.value = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // License Preview + Base64 Save
    licenseInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                if (file.type.startsWith('image/')) {
                    if (licensePreview) {
                        licensePreview.src = e.target.result;
                        licensePreview.style.display = 'block';
                    }
                    if (licensePDFPreview) licensePDFPreview.style.display = 'none';
                } else if (file.type === 'application/pdf') {
                    if (licensePreview) licensePreview.style.display = 'none';
                    if (licensePDFPreview) licensePDFPreview.style.display = 'block';
                }
                
                licenseBase64.value = e.target.result;
                
                // Update status text
                if (licenseStatus) {
                    licenseStatus.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Document attached.';
                    licenseStatus.className = 'mt-1 small text-success';
                } else {
                    const status = document.createElement('div');
                    status.id = 'licenseStatus';
                    status.className = 'mt-1 small text-success';
                    status.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Document attached.';
                    licenseInput.parentNode.appendChild(status);
                }
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>

<style>
.avatar-wrapper {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #007bff;
    position: relative;
    cursor: pointer;
}
.avatar-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.avatar-label {
    cursor: pointer;
    margin: 0;
}
</style>

@endsection
