@extends('backend.layouts.master')
@section('title', 'Customer Lists | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content p-4">

<!-- PAGE TITLE -->
<div class="mb-3">
    <h3>Customers</h3>
    <p class="text-muted mb-0">
        Manage B2B (Wholesale) and B2C (Retail) customers
    </p>
</div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Customers</span>
                <span class="info-box-number">18</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-building"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">B2B</span>
                <span class="info-box-number">12</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-secondary"><i class="fas fa-user"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">B2C</span>
                <span class="info-box-number">6</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Outstanding (B2B)</span>
                <span class="info-box-number">$8,400</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-3">
<div class="card-body">
<div class="row">

    <div class="col-md-3">
        <input class="form-control" placeholder="Search name / phone / code">
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Types</option>
            <option>B2B</option>
            <option>B2C</option>
        </select>
    </div>

    <div class="col-md-2">
        <select class="form-control">
            <option>All Status</option>
            <option>Active</option>
            <option>On Hold</option>
            <option>Blacklisted</option>
        </select>
    </div>

    <div class="col-md-3"></div>

    <div class="col-md-2 text-right">
        <button class="btn btn-success w-100"
                data-toggle="modal"
                data-target="#addCustomerModal">
            + Add Customer
        </button>
    </div>

</div>
</div>
</div>

<!-- ===================================================== -->
<!-- CUSTOMER TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>Customer Code</th>
    <th>Name</th>
    <th>Type</th>
    <th>Phone</th>
    <th>Credit Limit</th>
    <th>Outstanding</th>
    <th>Payment Rule</th>
    <th>Status</th>
    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

<!-- B2B -->
<tr>
    <td>CUS-B2B-001</td>
    <td>Phnom Penh Mobile Shop</td>
    <td><span class="badge badge-info">B2B</span></td>
    <td>012 888 999</td>
    <td>$20,000</td>
    <td>$3,200</td>
    <td><span class="badge badge-warning">Credit Allowed</span></td>
    <td><span class="badge badge-success">Active</span></td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-primary"
                data-toggle="modal"
                data-target="#viewCustomerModal">
            View
        </button>
    </td>
</tr>

<!-- B2C -->
<tr>
    <td>CUS-B2C-014</td>
    <td>Walk-in Customer</td>
    <td><span class="badge badge-secondary">B2C</span></td>
    <td>—</td>
    <td>—</td>
    <td>—</td>
    <td><span class="badge badge-success">Full Payment Only</span></td>
    <td><span class="badge badge-success">Active</span></td>
    <td class="text-center">
        <button class="btn btn-sm btn-outline-primary"
                data-toggle="modal"
                data-target="#viewCustomerModal">
            View
        </button>
    </td>
</tr>

</tbody>
</table>

</div>

<!-- PAGINATION -->
<div class="card-footer clearfix">
<ul class="pagination pagination-sm m-0 float-right">
    <li class="page-item disabled"><a class="page-link">«</a></li>
    <li class="page-item active"><a class="page-link">1</a></li>
    <li class="page-item"><a class="page-link">2</a></li>
    <li class="page-item"><a class="page-link">3</a></li>
    <li class="page-item"><a class="page-link">»</a></li>
</ul>
</div>

</div>

</section>
</div>

<!-- ===================================================== -->
<!-- ADD CUSTOMER MODAL -->
<!-- ===================================================== -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<!-- HEADER -->
<div class="modal-header bg-info text-white">
    <h5 class="modal-title">
        <i class="fas fa-user-plus mr-1"></i> Add Customer
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
</div>

<!-- BODY -->
<div class="modal-body">

<form>

    <!-- CUSTOMER TYPE -->
    <div class="form-group">
        <label>Customer Type</label>
        <select class="form-control">
            <option>B2B (Wholesale)</option>
            <option>B2C (Retail - Full Payment Only)</option>
        </select>

        <!-- PAYMENT RULE (RESTORED) -->
        <div class="alert alert-warning mt-2 mb-0 p-2">
            <i class="fas fa-exclamation-circle mr-1"></i>
            <strong>Payment Rule:</strong>
            B2C customers are <u>not allowed credit</u>. Full payment is required.
        </div>
    </div>

    <hr>

    <!-- BASIC INFO -->
    <div class="form-group">
        <label>Customer / Company Name</label>
        <input class="form-control" placeholder="Customer or company name">
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Phone</label>
                <input class="form-control" placeholder="Phone number">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" placeholder="Email address">
            </div>
        </div>
    </div>

    <!-- CREDIT LIMIT -->
    <div class="form-group">
        <label>Credit Limit (B2B Only)</label>
        <input class="form-control" placeholder="Disabled for B2C">
    </div>

    <!-- STATUS -->
    <div class="form-group">
        <label>Status</label>
        <select class="form-control">
            <option>Active</option>
            <option>On Hold</option>
        </select>
    </div>

</form>

</div>

<!-- FOOTER -->
<div class="modal-footer">
    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    <button class="btn btn-info">
        <i class="fas fa-save mr-1"></i> Save Customer
    </button>
</div>

</div>
</div>
</div>


<!-- ===================================================== -->
<!-- VIEW CUSTOMER MODAL -->
<!-- ===================================================== -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<!-- HEADER -->
<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="fas fa-user mr-1"></i> Customer Details
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
</div>

<!-- BODY -->
<div class="modal-body">

<div class="row">

    <!-- LEFT : CUSTOMER INFO -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">

                <h5 class="mb-3">
                    Phnom Penh Mobile Shop
                </h5>

                <p class="mb-2">
                    <i class="fas fa-building text-muted mr-1"></i>
                    <strong>Customer Type:</strong>
                    <span class="badge badge-info ml-1">B2B</span>
                </p>

                <p class="mb-2">
                    <i class="fas fa-phone text-muted mr-1"></i>
                    <strong>Phone:</strong> 012 888 999
                </p>

                <p class="mb-2">
                    <i class="fas fa-envelope text-muted mr-1"></i>
                    <strong>Email:</strong> —
                </p>

                <p class="mb-0">
                    <i class="fas fa-toggle-on text-muted mr-1"></i>
                    <strong>Status:</strong>
                    <span class="badge badge-success ml-1">Active</span>
                </p>

            </div>
        </div>
    </div>

    <!-- RIGHT : FINANCIAL INFO -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">

                <h6 class="mb-3">
                    <i class="fas fa-wallet mr-1"></i> Financial Summary
                </h6>

                <div class="mb-3">
                    <small class="text-muted">Credit Limit</small>
                    <h4 class="text-info">$20,000</h4>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Outstanding Balance</small>
                    <h4 class="text-danger">$3,200</h4>
                </div>

                <!-- PAYMENT RULE -->
                <div class="alert alert-warning p-2 mb-0">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <strong>Payment Rule:</strong>
                    Credit Allowed
                </div>

            </div>
        </div>
    </div>

</div>

</div>

<!-- FOOTER -->
<div class="modal-footer">
    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

</div>
</div>
</div>


@endsection
