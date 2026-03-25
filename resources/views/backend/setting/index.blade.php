@extends('backend.layouts.master')

@section('title', 'Advanced Profile Settings | Wholesale MGM')

@section('main-content')
<div class="content-wrapper bg-light">
    <!-- Breadcrumb & Page Header -->
    <div class="content-header border-bottom mb-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center py-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark font-weight-bold">
                        <i class="fas fa-user-shield mr-2 text-primary"></i>My Security & Profile
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="btn-group shadow-sm">
                        <a href="{{ route('export.activity') }}" class="btn btn-white btn-sm border font-weight-bold"><i class="fas fa-download mr-1"></i> Export Data</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                
                <!-- LEFT COLUMN: Identity & Quick Info -->
                <div class="col-xl-3 col-lg-4">
                    
                    <!-- Profile Identity Card -->
                    <div class="card card-primary card-outline shadow-sm border-0 mb-4">
                        <div class="card-body box-profile pb-4 pt-4">
                            <div class="text-center position-relative mb-4">
                                @php
                                    $profilePic = ($user->profile_picture) 
                                        ? asset('storage/' . $user->profile_picture) 
                                        : asset('assets/dist/img/MMOLOGO1.png');
                                    $roleModel = $user->role_id ? \App\Models\Role::find($user->role_id) : null;
                                    $roleName = $roleModel ? $roleModel->name : ucfirst($user->role);
                                @endphp
                                <div class="profile-pic-wrapper d-inline-block position-relative">
                                    <img class="profile-user-img img-fluid img-circle border-3 border-primary shadow-lg"
                                         src="{{ $profilePic }}"
                                         alt="User profile"
                                         id="side-avatar-preview"
                                         style="width: 130px; height: 130px; object-fit: cover;">
                                    <span class="status-indicator-lg {{ $user->status == 'active' ? 'bg-success' : 'bg-warning' }}" title="Status: {{ ucfirst($user->status) }}"></span>
                                </div>
                                <h3 class="profile-username text-center font-weight-bold mt-3 mb-1 text-primary">{{ $user->name }}</h3>
                                <div class="text-center mb-2">
                                    <span class="badge badge-info-soft text-uppercase px-3 py-1 small font-weight-bold" style="background: #e1f5fe; color: #01579b;">
                                        <i class="fas fa-user-tag mr-1"></i> {{ $roleName }}
                                    </span>
                                </div>
                                <p class="text-muted text-center small"><i class="fas fa-map-marker-alt mr-1"></i> Wholesale MGM Network</p>
                            </div>

                            <div class="d-flex justify-content-around border-top border-bottom py-3 mb-4">
                                <div class="text-center">
                                    <h6 class="font-weight-bold mb-0 text-primary">{{ \App\Models\AuditLog::where('user_id', $user->id)->count() }}</h6>
                                    <small class="text-muted text-uppercase smaller">Actions</small>
                                </div>
                                <div class="text-center border-left border-right px-3">
                                    <h6 class="font-weight-bold mb-0 text-success">Active</h6>
                                    <small class="text-muted text-uppercase smaller">Status</small>
                                </div>
                                <div class="text-center">
                                    <h6 class="font-weight-bold mb-0 text-info">{{ $user->created_at->format('M Y') }}</h6>
                                    <small class="text-muted text-uppercase smaller">Joined</small>
                                </div>
                            </div>

                            <ul class="list-group list-group-unbordered mb-0 no-border">
                                <li class="list-group-item px-0 py-2 d-flex justify-content-between border-0">
                                    <span class="text-muted"><i class="fas fa-envelope mr-2"></i>Email Verified</span> 
                                    <span class="text-success small font-weight-bold"><i class="fas fa-check-double"></i> Verified</span>
                                </li>
                                <li class="list-group-item px-0 py-2 d-flex justify-content-between border-0">
                                    <span class="text-muted"><i class="fas fa-history mr-2"></i>Last Updated</span> 
                                    <span class="font-weight-bold small">{{ $user->updated_at->format('d M, H:i') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Digital ID Card (Modern Content) -->
                    <div class="card bg-dark shadow-lg border-0 mb-4 overflow-hidden" style="border-radius: 15px; background: linear-gradient(135deg, #1a1a1a 0%, #333333 100%); min-height: 180px;">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <img src="{{ asset('assets/dist/img/MMOLOGO2.png') }}" style="height: 35px; filter: brightness(0) invert(1);" alt="MGM">
                                <i class="fas fa-rss text-white-50"></i>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-white-50 small mb-1 text-uppercase letter-spacing-1">Card Holder</h6>
                                <h5 class="text-white font-weight-bold text-uppercase mb-0">{{ $user->name }}</h5>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <h6 class="text-white-50 small mb-1 text-uppercase letter-spacing-1">Member ID</h6>
                                    <h6 class="text-white font-weight-bold mb-0 letter-spacing-2">MGM - {{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</h6>
                                </div>
                                <div class="bg-white p-1 rounded" style="width: 45px; height: 45px;">
                                    <i class="fas fa-qrcode fa-2x text-dark"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Stats -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold small"><i class="fas fa-tachometer-alt mr-2 text-primary"></i>PERFORMANCE METRICS</h3>
                        </div>
                        <div class="card-body p-0">
                            @if($supplier)
                            <div class="p-3 border-bottom">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-muted">Order Fulfillment</span>
                                    <span class="font-weight-bold text-success">98%</span>
                                </div>
                                <div class="progress progress-xxs"><div class="progress-bar bg-success" style="width: 98%"></div></div>
                            </div>
                            <div class="p-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-muted">Stock Availability</span>
                                    <span class="font-weight-bold text-primary">82%</span>
                                </div>
                                <div class="progress progress-xxs"><div class="progress-bar bg-primary" style="width: 82%"></div></div>
                            </div>
                            @else
                            <div class="p-3 border-bottom">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-muted">Approval Efficiency</span>
                                    <span class="font-weight-bold text-info">100%</span>
                                </div>
                                <div class="progress progress-xxs"><div class="progress-bar bg-info" style="width: 100%"></div></div>
                            </div>
                            <div class="p-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-muted">System Health</span>
                                    <span class="font-weight-bold text-warning">Healthy</span>
                                </div>
                                <div class="progress progress-xxs"><div class="progress-bar bg-warning" style="width: 100%"></div></div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Detailed Controls -->
                <div class="col-xl-9 col-lg-8">
                    <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
                        <div class="card-header p-0 bg-white">
                            <ul class="nav nav-tabs custom-main-tabs border-0" id="profileTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active font-weight-bold py-3 px-4" data-toggle="tab" href="#details-tab">
                                        <i class="fas fa-address-card mr-2"></i>Full Account Details
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold py-3 px-4" data-toggle="tab" href="#security-tab">
                                        <i class="fas fa-lock-open mr-2"></i>Security & Passwords
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold py-3 px-4" data-toggle="tab" href="#audit-tab">
                                        <i class="fas fa-fingerprint mr-2"></i>Security Audit Log
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-5">
                            <div class="tab-content">
                                
                                <!-- TAB 1: FULL ACCOUNT DETAILS -->
                                <div class="tab-pane fade show active" id="details-tab">
                                    <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <!-- Avatar Section -->
                                            <div class="col-md-12 text-center mb-5 border-bottom pb-5">
                                                <div class="avatar-edit-wrapper position-relative d-inline-block">
                                                    <img src="{{ $profilePic }}" 
                                                         class="img-thumbnail rounded-circle shadow-lg border-4 border-white" 
                                                         style="width: 180px; height: 180px; object-fit: cover; transition: 0.3s;" 
                                                         id="main-avatar-preview">
                                                    <label for="avatarInput" class="btn btn-primary btn-circle shadow-lg position-absolute" style="bottom: 10px; right: 10px; width: 45px; height: 45px; border: 3px solid #fff;">
                                                        <i class="fas fa-camera fa-lg"></i>
                                                    </label>
                                                    <input type="file" name="profile_picture" id="avatarInput" class="d-none" accept="image/*">
                                                </div>
                                                <h4 class="mt-3 font-weight-bold text-primary mb-1">{{ $user->name }}</h4>
                                                <p class="text-muted small">Update your profile identity and public representation</p>
                                            </div>
                                            
                                            <!-- Basic Info -->
                                            <div class="col-md-6 mb-4">
                                                <label class="font-weight-bold text-muted small text-uppercase mb-2 letter-spacing-1">Public Name</label>
                                                <div class="input-group input-group-lg shadow-none">
                                                    <div class="input-group-prepend"><span class="input-group-text bg-white"><i class="fas fa-user text-primary"></i></span></div>
                                                    <input type="text" name="name" class="form-control font-weight-bold" value="{{ old('name', $user->name) }}" required placeholder="Your full name">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-4">
                                                <label class="font-weight-bold text-muted small text-uppercase mb-2 letter-spacing-1">Email (System Fixed)</label>
                                                <div class="input-group input-group-lg shadow-none">
                                                    <div class="input-group-prepend"><span class="input-group-text bg-light"><i class="fas fa-envelope-square text-muted"></i></span></div>
                                                    <input type="email" class="form-control bg-light text-muted" value="{{ $user->email }}" readonly>
                                                </div>
                                            </div>

                                            @if($supplier)
                                            <!-- Supplier Detailed Data -->
                                            <div class="col-md-12 mt-4">
                                                <div class="p-4 rounded bg-light border border-dashed">
                                                    <h5 class="font-weight-bold text-dark mb-4 border-bottom pb-2">
                                                        <i class="fas fa-warehouse mr-2 text-info"></i>SUPPLIER BUSINESS PROFILE
                                                    </h5>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label class="small text-muted font-weight-bold text-uppercase">System Code</label>
                                                            <div class="bg-white p-2 rounded border font-weight-bold text-primary">{{ $supplier->code }}</div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="small text-muted font-weight-bold text-uppercase">Primary Brand</label>
                                                            <div class="bg-white p-2 rounded border font-weight-bold">{{ $supplier->brand ? $supplier->brand->name : 'N/A' }}</div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="small text-muted font-weight-bold text-uppercase">Lead Time</label>
                                                            <div class="bg-white p-2 rounded border font-weight-bold">{{ $supplier->lead_time_days }} Business Days</div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="small text-muted font-weight-bold text-uppercase">Primary Contact</label>
                                                            <div class="bg-white p-2 rounded border font-weight-bold text-dark">{{ $supplier->contact_person }}</div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="small text-muted font-weight-bold text-uppercase">Business Phone</label>
                                                            <div class="bg-white p-2 rounded border font-weight-bold text-dark">{{ $supplier->phone }}</div>
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <label class="small text-muted font-weight-bold text-uppercase">Registered Office Address</label>
                                                            <div class="bg-white p-3 rounded border text-dark">{{ $supplier->address }}</div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="small text-muted font-weight-bold text-uppercase">Terms of Payment</label>
                                                            <div class="badge badge-primary px-4 py-2" style="font-size: 0.9rem;">{{ $supplier->payment_term }}</div>
                                                        </div>
                                                        @if($supplier->document)
                                                        <div class="col-md-6 mb-3 text-right pt-4">
                                                            <a href="{{ asset('storage/' . $supplier->document) }}" target="_blank" class="btn btn-outline-info font-weight-bold btn-sm shadow-sm">
                                                                <i class="fas fa-file-pdf mr-1"></i> VIEW BUSINESS LICENSE
                                                            </a>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <div class="text-right mt-5 pt-4 border-top">
                                            <button type="reset" class="btn btn-light px-4 mr-2">Reset Changes</button>
                                            <button type="submit" class="btn btn-primary px-5 btn-lg shadow-lg font-weight-bold">
                                                <i class="fas fa-save mr-2"></i> COMMIT UPDATES
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- TAB 2: SECURITY & PASSWORDS -->
                                <div class="tab-pane fade" id="security-tab">
                                    <div class="row">
                                        <div class="col-md-7 border-right pr-md-5">
                                            <h5 class="font-weight-bold text-dark mb-4"><i class="fas fa-key mr-2 text-danger"></i>CHANGE LOGIN PASSWORD</h5>
                                            
                                            <form action="{{ route('setting.update') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="name" value="{{ $user->name }}">
                                                
                                                <div class="form-group mb-4">
                                                    <label class="font-weight-bold text-muted small text-uppercase mb-2">New Password</label>
                                                    <div class="input-group input-group-lg">
                                                        <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-shield-alt text-muted"></i></span></div>
                                                        <input type="password" name="password" id="passInput" class="form-control border-left-0 border-right-0" placeholder="••••••••">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-white border border-left-0 text-muted" type="button" onclick="togglePassword('passInput', this)">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="password-strength-meter mt-2">
                                                        <div class="progress progress-xxs"><div class="progress-bar bg-success" style="width: 0%"></div></div>
                                                        <small class="text-muted mt-1 d-block smaller">Minimum 8 characters with letters and numbers.</small>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group mb-5">
                                                    <label class="font-weight-bold text-muted small text-uppercase mb-2">Confirm New Password</label>
                                                    <div class="input-group input-group-lg">
                                                        <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-lock text-muted"></i></span></div>
                                                        <input type="password" name="password_confirmation" id="passConfirm" class="form-control border-left-0 border-right-0" placeholder="••••••••">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-white border border-left-0 text-muted" type="button" onclick="togglePassword('passConfirm', this)">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-danger btn-lg px-5 shadow-lg font-weight-bold">
                                                    <i class="fas fa-sync-alt mr-2"></i> UPDATE ACCESS PASSWORD
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <div class="col-md-5 pl-md-5 pt-4 pt-md-0">
                                            <h5 class="font-weight-bold text-dark mb-4"><i class="fas fa-user-shield mr-2 text-success"></i>PRIVACY & SESSIONS</h5>
                                            
                                            <div class="card bg-light border-0 shadow-none mb-4">
                                                <div class="card-body p-3">
                                                    <h6 class="font-weight-bold small mb-3">Security Checkpoints</h6>
                                                    <div class="custom-control custom-switch mb-3">
                                                        <input type="checkbox" class="custom-control-input" id="sw2fa" disabled>
                                                        <label class="custom-control-label font-weight-bold small" for="sw2fa">Multi-Factor Auth (Coming Soon)</label>
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="swLogin" checked>
                                                        <label class="custom-control-label font-weight-bold small" for="swLogin">Track My IP for Security</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <h6 class="font-weight-bold small text-muted mb-3"><i class="fas fa-desktop mr-2"></i>LAST 3 LOGIN SESSIONS</h6>
                                            @php
                                                $recentLogins = \App\Models\AuditLog::where('user_id', $user->id)
                                                    ->where('action', 'like', '%Viewed page: %')
                                                    ->latest()
                                                    ->take(3)
                                                    ->get();
                                            @endphp
                                            @foreach($recentLogins as $login)
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-white p-2 rounded shadow-sm mr-3 border">
                                                    <i class="fas fa-laptop text-primary"></i>
                                                </div>
                                                <div>
                                                    <p class="mb-0 font-weight-bold small">Chrome on Windows</p>
                                                    <small class="text-muted">{{ $login->ip_address }} • {{ $login->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 3: AUDIT LOG (AdminLTE Timeline Style) -->
                                <div class="tab-pane fade" id="audit-tab">
                                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                                        <h5 class="font-weight-bold mb-0 text-dark"><i class="fas fa-history mr-2 text-primary"></i>DETAILED ACTIVITY TIMELINE</h5>
                                        <span class="badge badge-primary px-3 py-2 font-weight-bold shadow-sm">LATEST 100 ACTIONS</span>
                                    </div>
                                    
                                    <div class="timeline-container px-2" style="max-height: 800px; overflow-y: auto;">
                                        <div class="timeline timeline-inverse">
                                            @php $lastDate = null; @endphp
                                            @forelse($logs as $log)
                                                @php 
                                                    $currentDate = $log->created_at->format('d M. Y'); 
                                                    $icon = 'fa-chevron-right';
                                                    $color = 'bg-primary';
                                                    
                                                    if(str_contains($log->action, 'Viewed')) { $icon = 'fa-eye'; $color = 'bg-info'; }
                                                    elseif(str_contains($log->action, 'Update')) { $icon = 'fa-user-edit'; $color = 'bg-warning'; }
                                                    elseif(str_contains($log->action, 'Created')) { $icon = 'fa-plus'; $color = 'bg-success'; }
                                                    elseif(str_contains($log->action, 'Delete') || str_contains($log->action, 'Denied')) { $icon = 'fa-trash'; $color = 'bg-danger'; }
                                                    elseif(str_contains($log->action, 'Login')) { $icon = 'fa-key'; $color = 'bg-purple'; }
                                                @endphp

                                                @if($lastDate !== $currentDate)
                                                    <div class="time-label">
                                                        <span class="bg-gray-dark px-3">{{ $currentDate }}</span>
                                                    </div>
                                                    @php $lastDate = $currentDate; @endphp
                                                @endif

                                                <div>
                                                    <i class="fas {{ $icon }} {{ $color }}"></i>
                                                    <div class="timeline-item shadow-none border">
                                                        <span class="time text-muted small"><i class="far fa-clock mr-1"></i>{{ $log->created_at->format('H:i:s A') }}</span>
                                                        <h3 class="timeline-header font-weight-bold text-dark border-0 pb-0">
                                                            {{ $log->action }}
                                                        </h3>
                                                        <div class="timeline-body pt-1 small text-muted">
                                                            <div class="d-flex justify-content-between">
                                                                <span><i class="fas fa-link mr-1"></i> {{ Str::limit($log->url, 80) }}</span>
                                                                <span><i class="fas fa-network-wired mr-1"></i> {{ $log->ip_address }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center py-5">
                                                    <i class="fas fa-history fa-4x mb-3 text-light"></i>
                                                    <p class="text-muted font-weight-bold">No activity footprint recorded yet.</p>
                                                </div>
                                            @endforelse
                                            
                                            @if($logs->isNotEmpty())
                                            <div>
                                                <i class="far fa-clock bg-gray"></i>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
</div>

<style>
    .status-indicator-lg {
        position: absolute;
        bottom: 8px;
        right: 25px;
        width: 24px;
        height: 24px;
        border: 5px solid #fff;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .custom-main-tabs .nav-link {
        border-bottom: 3px solid transparent;
        color: #888;
        font-size: 1rem;
        transition: 0.2s;
    }
    .custom-main-tabs .nav-link.active {
        background-color: transparent !important;
        border-bottom: 3px solid #007bff !important;
        color: #007bff !important;
    }
    .profile-user-img:hover { transform: scale(1.05); filter: brightness(0.95); cursor: pointer; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .letter-spacing-2 { letter-spacing: 2px; }
    .badge-info-soft { background: #e1f5fe; color: #01579b; }
    .no-border .list-group-item { border: 0 !important; }
    .opacity-50 { opacity: 0.5; }
    .progress-xxs { height: 4px; }
    .smaller { font-size: 0.7rem; }
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Avatar Preview
    $('#avatarInput').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#main-avatar-preview').attr('src', e.target.result);
                $('#side-avatar-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Handle Password Reveal Logic
    window.togglePassword = function(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    };
});
</script>
@endpush
