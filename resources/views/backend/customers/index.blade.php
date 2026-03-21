@extends('backend.layouts.master')
@section('title', 'Customer Lists | Wholesale MGM')
@section('main-content')
<div class="content-wrapper">
   <section class="content">
      <div class="content-header">
         <div class="container-fluid">
            <div class="row mb-2">
               <div class="col-sm-6">
                  <h3 class="m-0">Customers</h3>
                  <p class="text-muted mb-0">Manage B2B (Wholesale) and B2C (Retail) customers</p>
               </div>
               <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="#">Sale</a></li>
                     <li class="breadcrumb-item active">Customer</li>
                  </ol>
               </div>
            </div>
         </div>
      </div>

      <div class="row mb-4">
         <div class="col-md-3">
            <div class="info-box bg-light border">
               <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">Total Customers</span>
                  <span class="info-box-number h4 mb-0">{{ $totalCustomers }}</span>
               </div>
            </div>
         </div>
         <div class="col-md-3">
            <div class="info-box bg-light border">
               <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-building"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">B2B (Wholesale)</span>
                  <span class="info-box-number h4 mb-0">{{ $b2bCount }}</span>
               </div>
            </div>
         </div>
         <div class="col-md-3">
            <div class="info-box bg-light border">
               <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-user"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">B2C (Retail)</span>
                  <span class="info-box-number h4 mb-0">{{ $b2cCount }}</span>
               </div>
            </div>
         </div>
         <div class="col-md-3 text-right pt-3">
             <button class="btn btn-primary" data-toggle="modal" data-target="#addCustomerModal"><i class="fas fa-plus"></i> Add New Customer</button>
         </div>
      </div>

      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
         <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
         </button>
      </div>
      @endif

      <div class="card mb-4 border-0 shadow-sm">
         <div class="card-header bg-white">
            <h3 class="card-title text-muted"><i class="fas fa-filter mr-1"></i> Filter Search</h3>
         </div>
         <div class="card-body">
            <form action="{{ route('customers.index') }}" method="GET">
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, code, or phone..." value="{{ request('search') }}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control">
                           <option value="">All Types</option>
                           <option value="B2B" {{ request('type') == 'B2B' ? 'selected' : '' }}>B2B (Wholesale)</option>
                           <option value="B2C" {{ request('type') == 'B2C' ? 'selected' : '' }}>B2C (Retail)</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                           <option value="">All Status</option>
                           <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                           <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                           <option value="blacklisted" {{ request('status') == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Search</button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>

      <div class="card border-0 shadow-sm">
         <div class="card-body p-0">
            <div class="table-responsive">
               <table class="table table-hover mb-0">
                  <thead class="bg-light">
                     <tr>
                        <th class="border-top-0 px-4">Code</th>
                        <th class="border-top-0">Customer Name</th>
                        <th class="border-top-0">Type</th>
                        <th class="border-top-0">Phone</th>
                        <th class="border-top-0">Credit Limit</th>
                        <th class="border-top-0">Status</th>
                        <th class="border-top-0 text-center px-4">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($customers as $customer)
                     <tr>
                        <td class="px-4 font-weight-bold">{{ $customer->customer_code }}</td>
                        <td>
                           <div class="d-flex align-items-center">
                              <img src="{{ $customer->profile_picture ? asset($customer->profile_picture) : asset('assets/dist/img/MMOLOGO1.png') }}" 
                                   class="rounded-circle mr-2 border shadow-sm" 
                                   style="width: 35px; height: 35px; object-fit: cover;"
                                   alt="Avatar">
                              <span class="font-weight-bold text-dark">{{ $customer->name }}</span>
                           </div>
                        </td>
                        <td>
                           <span class="badge badge-{{ $customer->type == 'B2B' ? 'info' : 'secondary' }} px-3">
                              {{ $customer->type }}
                           </span>
                        </td>
                        <td>{{ $customer->phone ?: '—' }}</td>
                        <td class="font-weight-bold">{{ $customer->credit_limit ? '$' . number_format($customer->credit_limit, 2) : '—' }}</td>
                        <td>
                           @php
                              $statusClass = [
                                 'active' => 'success',
                                 'on_hold' => 'warning',
                                 'blacklisted' => 'danger'
                              ][$customer->status] ?? 'secondary';
                           @endphp
                           <span class="badge badge-{{ $statusClass }} px-2 py-1" style="min-width: 80px;">
                              {{ ucfirst(str_replace('_', ' ', $customer->status)) }}
                           </span>
                        </td>
                        <td class="text-center px-4">
                           <div class="btn-group">
                              <button class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#viewCustomerModal{{ $customer->id }}" title="View Details">
                                 <i class="fas fa-eye"></i>
                              </button>
                              <button class="btn btn-sm btn-outline-primary ml-1" data-toggle="modal" data-target="#editCustomerModal{{ $customer->id }}" title="Edit">
                                 <i class="fas fa-edit"></i>
                              </button>
                              <button class="btn btn-sm btn-outline-danger ml-1" data-toggle="modal" data-target="#deleteCustomerModal{{ $customer->id }}" title="Delete">
                                 <i class="fas fa-trash"></i>
                              </button>
                           </div>

                           {{-- Include Edit Modal for this specific customer --}}
                           @include('backend.customers.edit', ['customer' => $customer])

                           {{-- Delete Confirmation Modal --}}
                           <div class="modal fade" id="deleteCustomerModal{{ $customer->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                 <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-danger text-white">
                                       <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i> Confirm Delete</h5>
                                       <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <div class="modal-body text-center py-4">
                                       <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                                       <h5>Are you sure?</h5>
                                       <p class="text-muted">You are about to delete <strong>{{ $customer->name }}</strong>. This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer bg-light justify-content-center">
                                       <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cancel</button>
                                       <form action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="btn btn-danger px-4 shadow-sm">Yes, Delete</button>
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           {{-- View Modal (Simple version) --}}
                           <div class="modal fade" id="viewCustomerModal{{ $customer->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                              <div class="modal-dialog modal-lg" role="document">
                                 <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-info text-white">
                                       <h5 class="modal-title"><i class="fas fa-id-card mr-2"></i> Customer Details</h5>
                                       <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <div class="modal-body">
                                       <div class="row">
                                          <div class="col-md-4 text-center border-right">
                                             <img src="{{ $customer->profile_picture ? asset($customer->profile_picture) : asset('assets/dist/img/MMOLOGO1.png') }}" 
                                                  class="img-fluid rounded-circle shadow-sm mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                             <h4 class="font-weight-bold mb-0">{{ $customer->name }}</h4>
                                             <span class="badge badge-{{ $customer->type == 'B2B' ? 'info' : 'secondary' }} px-3 py-2 mt-2">{{ $customer->type }}</span>
                                          </div>
                                          <div class="col-md-8">
                                             <table class="table table-sm table-borderless mt-2">
                                                <tr><th width="35%">Customer Code:</th><td>{{ $customer->customer_code }}</td></tr>
                                                @if($customer->type == 'B2B')
                                                <tr><th>Contact Person:</th><td>{{ $customer->contact_person ?: '—' }}</td></tr>
                                                <tr><th>Tax Number:</th><td>{{ $customer->tax_number ?: '—' }}</td></tr>
                                                <tr><th>Payment Terms:</th><td>{{ $customer->payment_terms ?: '—' }}</td></tr>
                                                @endif
                                                <tr><th>Phone:</th><td>{{ $customer->phone ?: '—' }}</td></tr>
                                                <tr><th>Email:</th><td>{{ $customer->email ?: '—' }}</td></tr>
                                                <tr><th>Address:</th><td>{{ $customer->address ?: '—' }}</td></tr>
                                                <tr><th>Credit Limit:</th><td class="text-primary font-weight-bold">${{ number_format($customer->credit_limit, 2) }}</td></tr>
                                                <tr><th>Status:</th><td><span class="badge badge-{{ $customer->status == 'active' ? 'success' : ($customer->status == 'on_hold' ? 'warning' : 'danger') }}">{{ ucfirst($customer->status) }}</span></td></tr>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="modal-footer">
                                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                           <i class="fas fa-user-slash fa-3x mb-3"></i>
                           <p>No customers found matching your criteria.</p>
                        </td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
         <div class="card-footer bg-white border-top">
            <div class="d-flex justify-content-between align-items-center">
               <p class="text-muted mb-0 small">Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} entries</p>
               <div>
                  {{ $customers->appends(request()->query())->links('pagination::bootstrap-4') }}
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

@include('backend.customers.add')

@endsection
