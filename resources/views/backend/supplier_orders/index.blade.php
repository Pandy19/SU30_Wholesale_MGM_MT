@extends('backend.layouts.master')
@section('title', 'Supplier Order Historys | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content">


  <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Suppliers – Invoice Product Preview</h1>
                    <p class="text-muted mb-0">
                        Click a brand row to view suppliers for each electronic brand.
                    </p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Supliers</a></li>
                        <li class="breadcrumb-item active">Supplier Order History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info">
                <i class="fas fa-file-invoice"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Orders</span>
                <span class="info-box-number">{{ $summary['total_orders'] }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-dollar-sign"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Amount</span>
                <span class="info-box-number">${{ number_format($summary['total_amount'], 2) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Paid Orders</span>
                <span class="info-box-number">{{ $summary['paid_orders'] }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-clock"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Unpaid Orders</span>
                <span class="info-box-number">{{ $summary['unpaid_orders'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-3">
<div class="card-body">
<form action="" method="GET">
    <div class="row">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control"
                   placeholder="Search PO No (e.g. PO-0001)" value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select name="supplier" class="form-control">
                <option value="">All Suppliers</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="payment_status" class="form-control">
                <option value="">All Payment Status</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            </select>
        </div>

        <div class="col-md-3">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}" onchange="this.form.submit()">
        </div>
    </div>
</form>
</div>
</div>

<!-- ===================================================== -->
<!-- INVOICE TABLE -->
<!-- ===================================================== -->
<div class="card">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>PO No</th>
    <th>Supplier</th>
    <th>Order Date</th>
    <th>Invoice Date</th>
    <th>Total</th>
    <th>Payment</th>
    <th>Created By</th>

    <!-- NEW -->
    <th>Receiving Status</th>
    <th>Stock Status</th>

    <th class="text-center">Action</th>
</tr>
</thead>
<tbody>

@forelse($purchase_orders as $po)
<tr>
    <td>{{ $po->po_number }}</td>
    <td>{{ $po->supplier->company_name }}</td>
    <td>{{ $po->order_date ? $po->order_date->format('Y-m-d') : '-' }}</td>
    <td>{{ $po->created_at->format('Y-m-d') }}</td>
    <td>${{ number_format($po->total_amount, 2) }}</td>
    <td>
        @php
            $payStatus = strtolower($po->payment_status);
            $payBadge = $payStatus === 'paid' ? 'success' : ($payStatus === 'partial' ? 'info' : 'warning');
        @endphp
        <span class="badge badge-{{ $payBadge }}">{{ ucfirst($po->payment_status) }}</span>
    </td>
    <td>Admin</td>

    <!-- NEW -->
    <td>
        @php
            $status = strtolower($po->status);
            $statusBadge = [
                'pending' => 'info',
                'ordered' => 'primary',
                'received' => 'success',
                'completed' => 'success',
                'cancelled' => 'danger'
            ][$status] ?? 'secondary';
        @endphp
        <span class="badge badge-{{ $statusBadge }}">{{ ucfirst($po->status) }}</span>
    </td>
    <td>
        @if(in_array(strtolower($po->status), ['received', 'completed']))
            <span class="badge badge-success">Added to Stock</span>
        @else
            <span class="badge badge-warning">Not Added</span>
        @endif
    </td>

    <td class="text-center">
        <a href="{{ route('purchase_orders.confirm_payment', ['session_ids' => $po->id]) }}"
           class="btn btn-sm btn-primary">
            View Invoice
        </a>
        <!-- <button class="btn btn-sm btn-outline-secondary ml-1"
                onclick="window.print()">
            Print
        </button> -->
    </td>
</tr>
@empty
<tr>
    <td colspan="10" class="text-center py-4 text-muted">No purchase orders found.</td>
</tr>
@endforelse

</tbody>
</table>

</div>

<!-- ===================================================== -->
<!-- PAGINATION -->
<!-- ===================================================== -->
<div class="card-footer clearfix">
<ul class="pagination pagination-sm m-0 float-right">
    <li class="page-item disabled">
        <a class="page-link" href="#">«</a>
    </li>
    <li class="page-item active">
        <a class="page-link" href="#">1</a>
    </li>
    <li class="page-item">
        <a class="page-link" href="#">2</a>
    </li>
    <li class="page-item">
        <a class="page-link" href="#">3</a>
    </li>
    <li class="page-item">
        <a class="page-link" href="#">»</a>
    </li>
</ul>
</div>

</div>

</section>
</div>

@endsection
