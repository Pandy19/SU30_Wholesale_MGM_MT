@extends('backend.layouts.master')
@section('title', 'Customer Payment | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content">

<!-- ===================================================== -->
<!-- PAGE TITLE -->
<!-- ===================================================== -->
<div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Customer Payments</h1>
                    <p class="text-muted mb-0">
                    Track and receive payments from B2B & B2C customers
                    </p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Sale</a></li>
                        <li class="breadcrumb-item active">Customer Payments</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 mx-3" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info elevation-1">
                <i class="fas fa-file-invoice-dollar"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Invoices</span>
                <span class="info-box-number font-weight-bold">{{ number_format($totalInvoices) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success elevation-1">
                <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Paid Invoices</span>
                <span class="info-box-number font-weight-bold">{{ number_format($paidInvoices) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning elevation-1 text-white">
                <i class="fas fa-clock"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Pending Payments</span>
                <span class="info-box-number font-weight-bold text-warning">{{ number_format($pendingPayments) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-danger elevation-1">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Overdue Amount</span>
                <span class="info-box-number font-weight-bold text-danger">${{ number_format($overdueAmount, 2) }}</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-3 border-0 shadow-sm">
<div class="card-body">
<form action="{{ route('customer_payments.index') }}" method="GET">
    <div class="row">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control"
                   placeholder="Invoice # or Customer..." value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <select name="customer_id" class="form-control">
                <option value="">All Customers</option>
                @foreach($customers as $cust)
                    <option value="{{ $cust->id }}" {{ request('customer_id') == $cust->id ? 'selected' : '' }}>{{ $cust->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="type" class="form-control">
                <option value="">All Types</option>
                <option value="B2C" {{ request('type') == 'B2C' ? 'selected' : '' }}>Retail (B2C)</option>
                <option value="B2B" {{ request('type') == 'B2B' ? 'selected' : '' }}>Wholesale (B2B)</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="payment_status" class="form-control">
                <option value="">All Status</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="overdue" {{ request('payment_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
        </div>

        <div class="col-md-2">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>

        <div class="col-md-1 text-right">
            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Search</button>
        </div>
    </div>
</form>
</div>
</div>

<!-- ===================================================== -->
<!-- PAYMENT TABLE -->
<!-- ===================================================== -->
<div class="card border-0 shadow-sm">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light text-uppercase small">
<tr>
    <th class="pl-3">Invoice #</th>
    <th>Customer</th>
    <th class="text-center">Type</th>
    <th>Inv Date</th>
    <th>Due Date</th>
    <th class="text-right">Total</th>
    <th class="text-right text-success">Paid</th>
    <th class="text-right text-danger">Balance</th>
    <th class="text-center">Status</th>
    <th width="160" class="text-center">Action</th>
</tr>
</thead>

<tbody>
@forelse($sales_orders as $order)
    @php
        $isOverdue = ($order->payment_status != 'paid' && $order->due_date && strtotime($order->due_date) < time());
        $balance = $order->total_amount - $order->paid_amount;
    @endphp
<tr class="{{ $isOverdue ? 'table-danger' : '' }}">
    <td class="pl-3 font-weight-bold">INV-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
    <td>{{ $order->customer->name }}</td>
    <td class="text-center"><span class="badge border text-muted">{{ $order->customer->type }}</span></td>
    <td>{{ $order->order_date ? date('d/m/Y', strtotime($order->order_date)) : '—' }}</td>
    <td>
        @if($order->due_date)
            <span class="{{ $isOverdue ? 'text-danger font-weight-bold' : '' }}">
                {{ date('d/m/Y', strtotime($order->due_date)) }}
            </span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    <td class="text-right">${{ number_format($order->total_amount, 2) }}</td>
    <td class="text-right text-success">${{ number_format($order->paid_amount, 2) }}</td>
    <td class="text-right text-danger font-weight-bold">${{ number_format($balance, 2) }}</td>
    <td class="text-center">
        @if($isOverdue)
            <span class="badge badge-danger shadow-sm px-2">OVERDUE</span>
        @else
            @php
                $statusClass = [
                    'paid' => 'success',
                    'partial' => 'warning',
                    'unpaid' => 'secondary'
                ][$order->payment_status] ?? 'light';
            @endphp
            <span class="badge badge-{{ $statusClass }} shadow-sm px-2">{{ strtoupper($order->payment_status) }}</span>
        @endif
    </td>
    <td class="text-center">
        @if($order->payment_status != 'paid')
        <button class="btn btn-xs btn-success px-3 shadow-sm"
                data-toggle="modal"
                data-target="#receivePaymentModal{{ $order->id }}">
            <i class="fas fa-hand-holding-usd mr-1"></i> PAY
        </button>
        @else
        <span class="text-muted small"><i class="fas fa-check-double text-success mr-1"></i> Paid</span>
        @endif

        <!-- RECEIVE PAYMENT MODAL -->
        <div class="modal fade text-left" id="receivePaymentModal{{ $order->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                    <div class="modal-header bg-success text-white" style="border-radius: 15px 15px 0 0;">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-money-bill-wave mr-2"></i> Receive Payment
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('customer_payments.receive', $order->id) }}" method="POST">
                        @csrf
                        <div class="modal-body px-4 py-4">
                            <div class="d-flex justify-content-between mb-3 bg-light p-3 rounded">
                                <div>
                                    <span class="text-muted small text-uppercase font-weight-bold">Order Number</span>
                                    <div class="h6 mb-0">#{{ $order->order_number }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="text-muted small text-uppercase font-weight-bold">Remaining Balance</span>
                                    <div class="h6 mb-0 text-danger font-weight-bold">${{ number_format($balance, 2) }}</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold small text-muted">PAYMENT AMOUNT <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i class="fas fa-dollar-sign text-success"></i></span>
                                    </div>
                                    <input type="number" step="0.01" name="payment_amount" class="form-control border-left-0 font-weight-bold text-success" 
                                           max="{{ $balance }}" value="{{ $balance }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold small text-muted">METHOD</label>
                                        <select name="payment_method" class="form-control shadow-sm">
                                            <option value="cash">Cash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="digital_wallet">Digital Wallet (ABA/KHQR)</option>
                                            <option value="card">Credit Card</option>
                                            <option value="cheque">Cheque</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold small text-muted">DATE</label>
                                        <input type="date" name="payment_date" class="form-control shadow-sm" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="font-weight-bold small text-muted">REFERENCE / NOTES</label>
                                <textarea name="payment_note" class="form-control shadow-sm" rows="2" 
                                          placeholder="Transaction ID, Cheque number, etc."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-0" style="border-radius: 0 0 15px 15px;">
                            <button type="button" class="btn btn-secondary px-4 shadow-none" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success px-5 font-weight-bold shadow">
                                <i class="fas fa-check-circle mr-1"></i> CONFIRM PAYMENT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="10" class="text-center py-5 text-muted">
        <i class="fas fa-search fa-3x d-block mb-3 opacity-25"></i>
        No payment records found matching your criteria.
    </td>
</tr>
@endforelse
</tbody>
</table>

</div>

<!-- PAGINATION -->
<div class="card-footer bg-white border-top py-3">
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Showing {{ $sales_orders->firstItem() ?? 0 }} to {{ $sales_orders->lastItem() ?? 0 }} of {{ $sales_orders->total() }} total entries
        </div>
        <div>
            {{ $sales_orders->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

</div>

</section>
</div>

@endsection
