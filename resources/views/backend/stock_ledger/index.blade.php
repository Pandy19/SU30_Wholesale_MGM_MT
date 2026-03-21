@extends('backend.layouts.master')
@section('title', 'Stock Ledgers | Wholesale MGM')
@section('main-content')

<div class="content-wrapper">
<section class="content">

<!-- ===================================================== -->
<!-- PAGE TITLE + EXPORT -->
<!-- ===================================================== -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Stock Ledger <small class="text-muted">(Audit Trail)</small></h3>
        <p class="text-muted mb-0">
            Complete history of stock movements for audit and verification
        </p>
    </div>
    

    <div class="btn-group">
        <button class="btn btn-outline-secondary"
                onclick="window.print()">
            <i class="fas fa-print"></i>
        </button>

        <button class="btn btn-outline-danger">
            <i class="fas fa-file-pdf"></i>
        </button>

        <button class="btn btn-outline-success">
            <i class="fas fa-file-excel"></i>
        </button>
    </div>
</div>

<!-- ===================================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================================== -->
<div class="row mb-4">

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info">
                <i class="fas fa-list"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Records</span>
                <span class="info-box-number">{{ number_format($totalRecords) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success">
                <i class="fas fa-arrow-down"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Stock In</span>
                <span class="info-box-number">+{{ number_format($totalStockIn) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-danger">
                <i class="fas fa-arrow-up"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Stock Out</span>
                <span class="info-box-number">−{{ number_format($totalStockOut) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning">
                <i class="fas fa-user-check"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Actions Logged</span>
                <span class="info-box-number">100%</span>
            </div>
        </div>
    </div>

</div>

<!-- ===================================================== -->
<!-- FILTERS -->
<!-- ===================================================== -->
<div class="card mb-4 shadow-sm">
<div class="card-body">
<form action="{{ route('stock_ledger.index') }}" method="GET">
<div class="row">

    <div class="col-md-3 mb-2">
        <input type="text" name="search" class="form-control"
               placeholder="Search Product / SKU / Reference" value="{{ request('search') }}">
    </div>

    <div class="col-md-2 mb-2">
        <select name="product_id" class="form-control" onchange="this.form.submit()">
            <option value="">All Products</option>
            @foreach($products as $prod)
                <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2 mb-2">
        <select name="type" class="form-control" onchange="this.form.submit()">
            <option value="">All Actions</option>
            <option value="Stock In" {{ request('type') == 'Stock In' ? 'selected' : '' }}>Stock In</option>
            <option value="Stock Out" {{ request('type') == 'Stock Out' ? 'selected' : '' }}>Stock Out</option>
            <option value="Adjustment" {{ request('type') == 'Adjustment' ? 'selected' : '' }}>Adjustment</option>
        </select>
    </div>

    <div class="col-md-2 mb-2">
        <select name="user_id" class="form-control" onchange="this.form.submit()">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2 mb-2">
        <input type="date" name="date" class="form-control" onchange="this.form.submit()" value="{{ request('date') }}">
    </div>

    <div class="col-md-1 mb-2">
        <a href="{{ route('stock_ledger.index') }}" class="btn btn-outline-secondary btn-block">Reset</a>
    </div>

</div>
</form>
</div>
</div>

<!-- ===================================================== -->
<!-- LEDGER TABLE -->
<!-- ===================================================== -->
<div class="card shadow-sm">
<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">
<thead class="thead-light">
<tr>
    <th>Date</th>
    <th width="70" class="text-center">Image</th>
    <th>Product</th>
    <th>SKU</th>
    <th>Action</th>
    <th class="text-center">Qty In</th>
    <th class="text-center">Qty Out</th>
    <th class="text-center">Balance</th>
    <th>Reference</th>
    <th>Performed By</th>
    <th>Note</th>
</tr>
</thead>
<tbody>

@forelse($movements as $mov)
@php
    $badgeClass = 'badge-info';
    $isStockIn = ($mov->type == 'Stock In' || $mov->type == 'Initial Stock' || $mov->quantity > 0);
    
    if ($mov->type == 'Stock In') $badgeClass = 'badge-success';
    elseif ($mov->type == 'Stock Out') $badgeClass = 'badge-danger';
    elseif ($mov->type == 'Adjustment') $badgeClass = 'badge-warning';
    elseif ($mov->type == 'Initial Stock') $badgeClass = 'badge-info';

    $product = $mov->product;
    $imageUrl = $product->image ?? '';
    if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
        $imageUrl = asset('storage/' . $imageUrl);
    }
    if (!$imageUrl) {
        $imageUrl = asset('assets/dist/img/default-150x150.png');
    }
@endphp
<tr>
    <td>{{ $mov->created_at->format('Y-m-d') }}</td>
    <td class="text-center">
        <img src="{{ $imageUrl }}" class="img-thumbnail" width="40" style="height:40px; object-fit:contain;">
    </td>
    <td><strong>{{ $product->name ?? 'N/A' }}</strong></td>
    <td><small class="text-muted font-weight-bold">{{ $product->sku ?? 'N/A' }}</small></td>
    <td><span class="badge {{ $badgeClass }}">{{ $mov->type }}</span></td>
    
    <td class="text-center text-success">
        {!! $isStockIn ? '<strong>+'.abs($mov->quantity).'</strong>' : '—' !!}
    </td>
    <td class="text-center text-danger">
        {!! !$isStockIn ? '<strong>-'.abs($mov->quantity).'</strong>' : '—' !!}
    </td>

    <td class="text-center"><strong>{{ $mov->balance_after }}</strong></td>
    <td><span class="badge badge-light border">{{ $mov->reference ?? '—' }}</span></td>
    <td>{{ $mov->user->name ?? 'System' }}</td>
    <td><small class="text-muted italic">{{ $mov->notes }}</small></td>
</tr>
@empty
<tr>
    <td colspan="10" class="text-center py-5 text-muted">No records found in ledger.</td>
</tr>
@endforelse

</tbody>
</table>

</div>

<!-- ===================================================== -->
<!-- PAGINATION -->
<!-- ===================================================== -->
<div class="card-footer clearfix bg-white">
    <div class="float-right">
        {{ $movements->appends(request()->query())->links() }}
    </div>
</div>

</div>

</section>
</div>

@endsection
