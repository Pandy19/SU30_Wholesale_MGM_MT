@forelse($suppliers as $supplier)
    @php
        $userProfile = $supplier->user && $supplier->user->profile_picture 
            ? asset('storage/' . $supplier->user->profile_picture) 
            : asset('assets/dist/img/MMOLOGO1.png');
    @endphp
    <tr>
        <td class="text-center">
            <img src="{{ $userProfile }}" class="img-circle elevation-1" 
                 style="width: 45px; height: 45px; object-fit: cover; border: 1px solid #eee;">
        </td>
        <td class="font-weight-bold text-primary">{{ $supplier->code }}</td>
        <td>{{ $supplier->company_name }}</td>
        <td>
            <div class="small"><strong>Email:</strong> {{ $supplier->email }}</div>
            <div class="small text-muted"><strong>Phone:</strong> {{ $supplier->phone }}</div>
        </td>
        <td class="text-center">
            <span class="badge badge-info px-3 py-2" style="font-size: 0.9rem;">
                <i class="fas fa-boxes mr-1"></i> {{ $supplier->supplier_products_count }}
            </span>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-primary btn-sm px-3" 
                    onclick="loadSupplierProducts({{ $supplier->id }})"
                    style="border-radius: 8px;">
                <i class="fas fa-eye mr-1"></i> View & Manage Posts
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-5">
            <div class="text-muted">
                <i class="fas fa-search fa-2x mb-3"></i><br>
                No suppliers found matching your criteria.
            </div>
        </td>
    </tr>
@endforelse
