@extends('backend.layouts.master')
@section('title', 'Manage Orders | Supplier Dashboard')

@push('styles')
<style>
    .process-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 25px;
        position: relative;
    }
    .process-line::before {
        content: "";
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background: #dee2e6;
        z-index: 1;
    }
    .process-step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    .step-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #dee2e6;
        display: inline-block;
        line-height: 26px;
        font-weight: bold;
        color: #adb5bd;
    }
    .process-step.active .step-icon {
        background: #007bff;
        border-color: #007bff;
        color: #fff;
    }
    .process-step.completed .step-icon {
        background: #28a745;
        border-color: #28a745;
        color: #fff;
    }
    .step-text {
        display: block;
        font-size: 11px;
        margin-top: 5px;
        color: #6c757d;
        font-weight: 500;
    }
    .process-step.active .step-text {
        color: #007bff;
        font-weight: bold;
    }
    /* Specific Pagination Styling to match your request */
    .pagination-footer .pagination {
        margin: 0 !important;
        float: right !important;
    }
    .pagination-footer .pagination .page-link {
        padding: .25rem .5rem;
        font-size: .875rem;
        line-height: 1.5;
    }
</style>
@endpush

@section('main-content')

<div class="content-wrapper">
<section class="content">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0 text-dark font-weight-bold">Manage Orders</h3>
                    <p class="text-muted mb-0">Track and process your incoming purchase orders</p>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('Supplier_Dashboard.index') }}">Supplier</a></li>
                        <li class="breadcrumb-item active">Manage Orders</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="card mb-3 mx-3 shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('supplier.orders.manage') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="small text-muted font-weight-bold">Search PO Number</label>
                        <input type="text" name="search" class="form-control" placeholder="Search PO Number..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted font-weight-bold">Filter Status</label>
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Search & Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ORDERS TABLE -->
    <div class="card mx-3 shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold">Order History ({{ $purchase_orders->total() }} Records)</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>PO Number</th>
                        <th>Order Date</th>
                        <th>Products</th>
                        <th>Terms & Info</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchase_orders as $po)
                    <tr>
                        <td><strong class="text-primary">{{ $po->po_number }}</strong></td>
                        <td>{{ $po->order_date ? $po->order_date->format('d/m/Y') : $po->created_at->format('d/m/Y') }}</td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                @foreach($po->items as $item)
                                    <li><small>{{ $item->product->name }} (x{{ $item->quantity }})</small></li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <div class="small">
                                <strong>Due:</strong> {{ $po->due_date ? $po->due_date->format('d/m/Y') : 'N/A' }}<br>
                                <strong>Term:</strong> {{ $po->payment_term ?? 'N/A' }}<br>
                                <strong>Lead:</strong> {{ $po->supplier->lead_time_days ?? '0' }} Days
                            </div>
                        </td>
                        <td class="font-weight-bold text-dark">${{ number_format($po->total_amount, 2) }}</td>
                        <td>
                            @php
                                $payStatus = strtolower($po->payment_status);
                                $payBadge = $payStatus === 'paid' ? 'success' : 'warning';
                            @endphp
                            <span class="badge badge-{{ $payBadge }} px-2 py-1">{{ ucfirst($po->payment_status) }}</span>
                            @if($payStatus === 'paid')
                                <div class="small text-success mt-1"><i class="fas fa-check-circle"></i> Paid</div>
                            @else
                                <div class="small text-muted mt-1"><i class="far fa-clock"></i> Wait for Admin</div>
                            @endif
                        </td>
                        <td>
                            @php
                                $status = strtolower($po->status);
                                $statusBadge = [
                                    'pending' => 'info',
                                    'ordered' => 'primary',
                                    'shipped' => 'warning',
                                    'delivered' => 'success',
                                    'received' => 'success',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ][$status] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $statusBadge }} px-2 py-1">{{ ucfirst($po->status) }}</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info shadow-sm" data-toggle="modal" data-target="#updateStatusModal{{ $po->id }}">
                                Update Process
                            </button>
                            <a href="{{ route('purchase_orders.confirm_payment', ['session_ids' => $po->id]) }}" class="btn btn-sm btn-outline-secondary shadow-sm mt-1 mt-md-0">
                                <i class="fas fa-eye"></i> Invoice
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-3x mb-3 d-block opacity-25"></i>
                            No purchase orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- PAGINATION - EXACTLY AS REQUESTED -->
        <div class="card-footer clearfix bg-white pagination-footer">
            <div class="float-left text-muted small mt-2">
                Showing {{ $purchase_orders->firstItem() ?? 0 }} to {{ $purchase_orders->lastItem() ?? 0 }} of {{ $purchase_orders->total() }} entries
            </div>
            {{ $purchase_orders->appends(request()->query())->links() }}
        </div>
    </div>

</section>
</div>

<!-- MODALS -->
@foreach($purchase_orders as $po)
<div class="modal fade" id="updateStatusModal{{ $po->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('supplier.orders.update_status', $po->id) }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold text-dark">Update Order: {{ $po->po_number }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <!-- PROCESS LINE -->
                    <div class="process-line px-4">
                        @php
                            $currentStatus = strtolower($po->status);
                            $steps = [
                                'pending'   => ['icon' => '1', 'text' => 'Offer'],
                                'ordered'   => ['icon' => '2', 'text' => 'Processing'],
                                'shipped'   => ['icon' => '3', 'text' => 'Shipping'],
                                'delivered' => ['icon' => '4', 'text' => 'Delivered'],
                                'received'  => ['icon' => '5', 'text' => 'Received']
                            ];
                            $statusOrderList = ['pending', 'ordered', 'shipped', 'delivered', 'received', 'completed'];
                            $currentIndex = array_search($currentStatus, $statusOrderList);
                        @endphp
                        
                        @foreach($steps as $key => $step)
                            @php
                                $stepIndex = array_search($key, $statusOrderList);
                                $class = '';
                                if ($stepIndex < $currentIndex) $class = 'completed';
                                elseif ($stepIndex === $currentIndex) $class = 'active';
                            @endphp
                            <div class="process-step {{ $class }}">
                                <span class="step-icon shadow-sm">
                                    @if($class === 'completed') <i class="fas fa-check"></i> @else {{ $step['icon'] }} @endif
                                </span>
                                <span class="step-text">{{ $step['text'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- STEP 1: CONFIRM PRODUCTS -->
                    <div class="bg-light p-3 rounded mb-4 border shadow-sm">
                        <h5 class="mb-3 font-weight-bold text-dark"><i class="fas fa-boxes mr-2 text-primary"></i> 1. Products to Send</h5>
                        <p class="small text-muted mb-3">Please confirm the quantity you are sending to match the Admin's order.</p>
                        
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered bg-white mb-0 shadow-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Product Info</th>
                                        <th class="text-center text-primary">Qty Admin Needs</th>
                                        <th class="text-center" style="width: 140px;">Your Qty to Send</th>
                                        <th class="text-right">Unit Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($po->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $imageUrl = $item->product->image;
                                                    if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                                        $imageUrl = asset('storage/' . $imageUrl);
                                                    }
                                                    if (!$imageUrl) {
                                                        $imageUrl = asset('assets/dist/img/default-150x150.png');
                                                    }
                                                @endphp
                                                <img src="{{ $imageUrl }}"
                                                     class="img-thumbnail mr-3 shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                                <div>
                                                    <div class="font-weight-bold text-dark">{{ $item->product->name }}</div>
                                                    <small class="text-muted">{{ $item->product->sku }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle h5 text-primary font-weight-bold">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="text-center align-middle">
                                            <input type="number" 
                                                   name="items[{{ $item->id }}][shipped_qty]" 
                                                   class="form-control text-center font-weight-bold border-primary shadow-sm" 
                                                   value="{{ $item->quantity }}" 
                                                   min="0" max="{{ $item->quantity }}">
                                            <input type="hidden" name="items[{{ $item->id }}][product_id]" value="{{ $item->product_id }}">
                                        </td>
                                        <td class="text-right align-middle font-weight-bold text-dark">${{ number_format($item->unit_cost, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- STEP 2: UPDATE STATUS -->
                    <div class="p-2">
                        <h5 class="mb-3 font-weight-bold text-dark"><i class="fas fa-sync-alt mr-2 text-primary"></i> 2. Process Order Status</h5>
                        <div class="row mb-3">
                            <div class="col-md-6 border-right">
                                <label class="text-muted small mb-1">CURRENT STATUS</label>
                                <div class="h5">
                                    @php
                                        $currentStatusBadge = [
                                            'pending' => 'info',
                                            'ordered' => 'primary',
                                            'shipped' => 'warning',
                                            'delivered' => 'success',
                                            'received' => 'success',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ][$currentStatus] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-{{ $currentStatusBadge }} py-2 px-3 shadow-sm">
                                        {{ ucfirst($po->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">UPDATE STATUS TO</label>
                                <select name="status" class="form-control font-weight-bold shadow-sm" required>
                                    <option value="ordered" {{ $po->status == 'ordered' ? 'selected' : '' }}>Processing (Ordered)</option>
                                    <option value="shipped" {{ $po->status == 'shipped' ? 'selected' : '' }}>Sanding on the way (Shipped)</option>
                                    <option value="delivered" {{ $po->status == 'delivered' ? 'selected' : '' }}>Delivered to Warehouse</option>
                                    <option value="cancelled" {{ $po->status == 'cancelled' ? 'selected' : '' }}>Cancelled / Drop</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-muted small mb-1 font-weight-bold">REMARKS / TRACKING INFO</label>
                            <textarea name="remarks" class="form-control shadow-sm" rows="2" placeholder="Add tracking info or notes for the warehouse">{{ $po->remarks }}</textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary shadow-sm px-4" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm font-weight-bold">Update Order</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection
