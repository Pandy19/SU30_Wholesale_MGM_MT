@extends('backend.layouts.master')
@section('title', 'Sales Order History | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Sales Order History</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Sales History</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Summary Stats (Small Boxes - Classic AdminLTE) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($totalSales) }}</h3>
                            <p>Total Orders</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>${{ number_format($totalRevenue, 2) }}</h3>
                            <p>Total Revenue</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($pendingPayment) }}</h3>
                            <p>Pending Payments</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ number_format($b2bCustomersCount) }}</h3>
                            <p>B2B Customers</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="card card-default card-outline">
                <div class="card-header">
                    <h3 class="card-title text-muted"><i class="fas fa-filter mr-1"></i> Filter Records</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('sales_order_history.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Search</label>
                                    <input type="text" name="search" class="form-control" placeholder="Order # or Customer..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Client Type</label>
                                    <select name="type" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="B2C" {{ request('type') == 'B2C' ? 'selected' : '' }}>Retail (B2C)</option>
                                        <option value="B2B" {{ request('type') == 'B2B' ? 'selected' : '' }}>Wholesale (B2B)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Payment</label>
                                    <select name="payment_status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- GROUPED TABLE -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sales Order History Records</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped table-valign-middle mb-0">
                        <thead class="thead-light text-uppercase small">
                            <tr>
                                <th class="pl-4">Invoice #</th>
                                <th>Order Ref</th>
                                <th>Customer</th>
                                <th class="text-center">Type</th>
                                <th>Date</th>
                                <th class="text-right">Total Amount</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $groupedOrders = $sales_orders->groupBy('customer_id'); 
                            @endphp

                            @forelse($groupedOrders as $customerId => $orders)
                                @php $customer = $orders->first()->customer; @endphp
                                
                                <!-- CUSTOMER GROUP HEADER ROW -->
                                <tr class="bg-gray-light customer-group-header" style="cursor: pointer;" data-target="group-{{ $customerId }}">
                                    <td colspan="7" class="py-2 pl-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $customer->profile_picture ? asset($customer->profile_picture) : asset('assets/dist/img/MMOLOGO1.png') }}" 
                                                 class="img-circle border elevation-1 mr-3" width="30" height="30" style="object-fit: cover;">
                                            <div>
                                                <span class="h6 mb-0 font-weight-bold">CUSTOMER: {{ strtoupper($customer->name) }}</span>
                                                <span class="ml-2 badge badge-info">{{ $customer->type }}</span>
                                                <small class="ml-2 text-muted">({{ $orders->count() }} Invoices)</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-2">
                                        <button class="btn btn-xs btn-info px-3 btn-toggle-group" data-target="group-{{ $customerId }}">
                                            <i class="fas fa-eye mr-1"></i> VIEW
                                        </button>
                                    </td>
                                </tr>

                                <!-- INVOICE ROWS (HIDDEN BY DEFAULT) -->
                                @foreach($orders as $order)
                                <tr class="invoice-row group-{{ $customerId }}" style="display: none;">
                                    <td class="pl-5 font-weight-bold">INV-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td class="text-center">{{ $customer->type }}</td>
                                    <td>{{ $order->order_date ? date('d/m/Y', strtotime($order->order_date)) : '—' }}</td>
                                    <td class="text-right text-primary font-weight-bold">${{ number_format($order->total_amount, 2) }}</td>
                                    <td class="text-center">
                                        @php
                                            $statusClass = [
                                                'paid' => 'success',
                                                'partial' => 'warning',
                                                'unpaid' => 'danger'
                                            ][$order->payment_status] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $statusClass }}">
                                            {{ strtoupper($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('sales_order.confirm_sale', $order->id) }}" class="btn btn-xs btn-default border">
                                            <i class="fas fa-file-invoice mr-1"></i> Invoice
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">No records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-left">
                        Showing {{ $sales_orders->firstItem() ?? 0 }} to {{ $sales_orders->lastItem() ?? 0 }} of {{ $sales_orders->total() }} entries
                    </div>
                    <div class="float-right">
                        {{ $sales_orders->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

@push('styles')
<style>
    .bg-gray-light { background-color: #f4f6f9; }
    .customer-group-header:hover { background-color: #e9ecef; }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('.customer-group-header').click(function() {
        const targetClass = $(this).data('target');
        const btn = $(`.btn-toggle-group[data-target="${targetClass}"]`);
        const rows = $(`.${targetClass}`);
        
        rows.toggle();
        
        if (rows.is(':visible')) {
            btn.removeClass('btn-info').addClass('btn-secondary').html('<i class="fas fa-eye-slash mr-1"></i> HIDE');
        } else {
            btn.removeClass('btn-secondary').addClass('btn-info').html('<i class="fas fa-eye mr-1"></i> VIEW');
        }
    });
});
</script>
@endpush

@endsection
