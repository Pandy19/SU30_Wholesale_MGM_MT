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

                        <form method="POST" action="#" enctype="multipart/form-data">
                            @csrf

                            <!-- AVATAR -->
                            <div class="form-group text-center mb-3">
                                <label class="d-block font-weight-bold mb-2">
                                    Profile Avatar
                                </label>

                                <div class="avatar-wrapper mx-auto">
                                    <label for="avatarInput" class="avatar-label">
                                        <img
                                            src="{{ asset('assets/dist/img/MMOLOGO.png') }}"
                                            id="avatarPreview"
                                            alt="Avatar">
                                    </label>

                                    <input type="file"
                                           id="avatarInput"
                                           name="avatar"
                                           accept="image/*"
                                           hidden>
                                </div>

                                <small class="text-muted d-block mt-2">
                                    Click the image to upload (JPG / PNG)
                                </small>
                            </div>

                            <!-- ROLE -->
                            <div class="form-group">
                                <label>Register As</label>
                                <select name="role"
                                        id="roleSelect"
                                        class="form-control"
                                        required>
                                    <option value="">-- Select Role --</option>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                    <option value="inspector">Inspector</option>
                                    <option value="accountant">Accountant</option>
                                    <option value="supplier">Supplier</option>
                                </select>
                            </div>

                            <hr class="my-3">

                            <!-- BASIC INFO -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Full Name</label>
                                    <input type="text"
                                           class="form-control"
                                           placeholder="Enter full name"
                                           required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="email"
                                           class="form-control"
                                           placeholder="Enter email"
                                           required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Password</label>
                                    <input type="password"
                                           class="form-control"
                                           placeholder="Password"
                                           required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Confirm Password</label>
                                    <input type="password"
                                           class="form-control"
                                           placeholder="Confirm password"
                                           required>
                                </div>
                            </div>

                            <!-- SUPPLIER VERIFICATION -->
                            <div id="supplierFields" style="display:none;">
                                <hr class="my-3">

                                <h6 class="text-warning font-weight-bold mb-2">
                                    Supplier Verification
                                </h6>

                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text"
                                           class="form-control"
                                           placeholder="Registered company name">
                                </div>

                                <div class="form-group">
                                    <label>Company ID / Business License</label>
                                    <input type="text"
                                           class="form-control"
                                           placeholder="Company ID number">
                                </div>

                                <div class="form-group">
                                    <label>Upload ID Card / License</label>
                                    <input type="file" class="form-control-file">
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

@endsection
