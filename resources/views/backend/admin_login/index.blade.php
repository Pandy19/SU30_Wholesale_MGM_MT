@extends('backend.layouts.auth')

@section('title', 'Login | Wholesale MGM')

@section('content')

<div class="container-fluid">
<div class="row justify-content-center align-items-center" style="min-height:100vh;">

    <div class="col-lg-8 col-md-10">

        <div class="card shadow-lg">
            <div class="row no-gutters">

                <!-- LEFT SIDE : LOGO -->
                <div class="col-md-5 bg-primary text-white d-flex align-items-center justify-content-center">
                    <div class="text-center p-4">
                        <img src="{{ asset('assets/dist/img/MMOLOGO.png') }}"
                             alt="Wholesale MGM Logo"
                             style="width:260px; max-width:100%; height:auto;">

                        <h3 class="mt-3 font-weight-bold">Wholesale MGM</h3>
                        <p class="mb-0">Management System</p>
                    </div>
                </div>

                <!-- RIGHT SIDE : LOGIN FORM -->
                <div class="col-md-7">
                    <div class="card-body p-5">

                        <h4 class="mb-3 font-weight-bold">Backend Login</h4>
                        <p class="text-muted mb-4">Please sign in to continue</p>

                        <form method="POST" action="{{ route('admin_login.submit') }}">
                            @csrf

                            <!-- ROLE -->
                            <div class="form-group">
                                <label>Login As</label>
                                <select name="role" class="form-control" required>
                                    <option value="">-- Select Role --</option>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                    <option value="inspector">Inspector</option>
                                    <option value="accountant">Accountant</option>
                                    <option value="supplier">Supplier</option>
                                </select>
                            </div>

                            <!-- EMAIL -->
                            <div class="form-group">
                                <label>Email</label>
                                <div class="input-group">
                                    <input type="email" name="email"
                                           class="form-control"
                                           placeholder="Enter email" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- PASSWORD -->
                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <input type="password" name="password"
                                           class="form-control"
                                           placeholder="Enter password" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- REMEMBER -->
                            <div class="form-group">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember">
                                    <label for="remember">Remember Me</label>
                                </div>
                            </div>

                            <!-- LOGIN -->
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                Sign In
                            </button>

                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    Don’t have an account?
                                    <a href="{{ url('/admin_register') }}" class="font-weight-bold">
                                        Create one
                                    </a>
                                </small>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

        <div class="text-center mt-3 text-muted">
            <small>&copy; {{ date('Y') }} Wholesale MGM</small>
        </div>

    </div>

</div>
</div>

@endsection
