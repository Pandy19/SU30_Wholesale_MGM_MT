@extends('backend.layouts.master')
@section('title', 'Supplier Approvals | MMO Wholesale')

@section('main-content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-user-check mr-2 text-primary"></i>Supplier Approvals
                    </h1>
                    <p class="text-muted mb-0">Manage and verify new supplier registration requests.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
                        <li class="breadcrumb-item active">Approvals</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Summary Info Boxes (Optional but looks professional) -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hourglass-half"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending Requests</span>
                            <span class="info-box-number">{{ $pendingSuppliers->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Active Suppliers</span>
                            <span class="info-box-number">{{ $activeSuppliersCount }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-paper-plane"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Email Service</span>
                            <span class="info-box-number text-success">Connected</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box shadow-sm bg-light">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-info-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Manual Review</span>
                            <span class="info-box-number">Required</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-list mr-1"></i> Registration Queue
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 25%">Supplier Information</th>
                                    <th style="width: 20%">Contact Person</th>
                                    <th style="width: 15%">Business Terms</th>
                                    <th style="width: 15%">Verification</th>
                                    <th style="width: 10%">Submitted</th>
                                    <th style="width: 15%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingSuppliers as $supplier)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $userProfile = ($supplier->user && $supplier->user->profile_picture) 
                                                    ? asset('storage/' . $supplier->user->profile_picture) 
                                                    : null;
                                                
                                                $brandLogo = ($supplier->brand && $supplier->brand->logo) 
                                                    ? asset('storage/' . $supplier->brand->logo) 
                                                    : null;
                                            @endphp

                                            @if($userProfile)
                                                <div class="mr-3 shadow-sm border rounded-circle overflow-hidden bg-white" style="width: 45px; height: 45px;">
                                                    <img src="{{ $userProfile }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            @elseif($brandLogo)
                                                <div class="mr-3 shadow-sm border rounded-circle overflow-hidden bg-white d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                                    <img src="{{ $brandLogo }}" alt="{{ $supplier->brand->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                </div>
                                            @else
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 45px; height: 45px; font-size: 1.2rem; font-weight: bold;">
                                                    {{ strtoupper(substr($supplier->company_name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold text-dark">{{ $supplier->company_name }}</div>
                                                <small class="text-primary font-weight-bold">{{ $supplier->code }}</small>
                                                <div class="small text-muted mt-1">
                                                    <i class="fas fa-map-marker-alt mr-1"></i> {{ Str::limit($supplier->address, 30) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold mb-1">{{ $supplier->contact_person }}</div>
                                        <div class="small"><i class="fas fa-envelope mr-1 text-muted"></i> {{ $supplier->email }}</div>
                                        <div class="small"><i class="fas fa-phone mr-1 text-muted"></i> {{ $supplier->phone }}</div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info-soft border border-info px-2 py-1 mb-1 text-info" style="background-color: #e3f2fd;">
                                            {{ $supplier->payment_term }}
                                        </span>
                                        <div class="small text-muted mt-1">
                                            <i class="fas fa-shipping-fast mr-1"></i> {{ $supplier->lead_time_days }} Days LT
                                        </div>
                                    </td>
                                    <td>
                                        @if($supplier->document)
                                            <a href="{{ asset('storage/' . $supplier->document) }}" target="_blank" class="btn btn-sm btn-outline-primary btn-block text-left shadow-xs">
                                                <i class="fas fa-file-contract mr-2"></i>View License
                                            </a>
                                        @else
                                            <span class="text-muted italic small"><i class="fas fa-times-circle mr-1"></i>No Document</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $supplier->created_at->format('d M, Y') }}</span>
                                        <div class="small text-muted">{{ $supplier->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm">
                                            <button type="button" class="btn btn-success btn-sm px-3" 
                                                    onclick="openApproveModal('{{ $supplier->id }}', '{{ $supplier->company_name }}')"
                                                    data-toggle="tooltip" title="Approve Registration">
                                                <i class="fas fa-check mr-1"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm px-3" 
                                                    onclick="openDenyModal('{{ $supplier->id }}', '{{ $supplier->company_name }}')"
                                                    data-toggle="tooltip" title="Deny & Remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 bg-white">
                                        <div class="py-5">
                                            <div class="mb-3">
                                                <i class="fas fa-clipboard-check fa-4x text-light"></i>
                                            </div>
                                            <h4 class="text-muted">All Caught Up!</h4>
                                            <p class="text-muted mb-0">There are no pending supplier registration requests at this time.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($pendingSuppliers->count() > 0)
                <div class="card-footer bg-white border-top-0 py-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i> Total of {{ $pendingSuppliers->count() }} pending registrations awaiting your decision.
                    </small>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white border-0 py-3">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-check-circle mr-2"></i>Approve Registration
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-user-check fa-5x text-success animate__animated animate__bounceIn"></i>
                    </div>
                    <h4 class="mb-2">Ready to Approve?</h4>
                    <p class="text-muted">You are about to activate <strong><span id="approveSupplierName" class="text-primary"></span></strong> as an official supplier.</p>
                    <div class="alert alert-info py-2 small mt-3">
                        <i class="fas fa-envelope mr-1"></i> An automated approval email will be sent to the supplier immediately.
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-light btn-lg px-4 mr-2 border" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-lg px-5 shadow">Activate Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Deny Modal -->
<div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0 py-3">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Confirm Rejection
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="denyForm" method="POST">
                @csrf
                <div class="modal-body text-center p-5">
                    <div class="mb-4 text-danger">
                        <i class="fas fa-user-slash fa-5x animate__animated animate__shakeX"></i>
                    </div>
                    <h4 class="mb-2">Deny Registration?</h4>
                    <p class="text-muted">Are you sure you want to reject the application from <strong><span id="denySupplierName" class="text-primary"></span></strong>?</p>
                    <div class="bg-danger-light p-3 rounded mt-3" style="background-color: #fff5f5; border: 1px dashed #feb2b2;">
                        <p class="text-danger small mb-0 font-weight-bold">
                            <i class="fas fa-trash-alt mr-1"></i> THIS ACTION WILL PERMANENTLY DELETE THE REQUEST.
                        </p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-light btn-lg px-4 mr-2 border" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-lg px-5 shadow">Deny & Remove</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

function openApproveModal(id, name) {
    $('#approveSupplierName').text(name);
    $('#approveForm').attr('action', '/suppliers/approve/' + id);
    $('#approveModal').modal('show');
}

function openDenyModal(id, name) {
    $('#denySupplierName').text(name);
    $('#denyForm').attr('action', '/suppliers/deny/' + id);
    $('#denyModal').modal('show');
}
</script>
@endpush
