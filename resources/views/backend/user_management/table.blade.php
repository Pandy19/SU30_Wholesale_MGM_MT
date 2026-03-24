<table class="table table-hover text-nowrap">
    <thead>
        <tr>
            <th>User Code</th>
            <th>Avatar</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Joined Date</th>
            <th width="100px">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
            <tr>
                <td>
                    <span class="text-primary font-weight-bold">{{ $user->user_code ?? 'N/A' }}</span>
                </td>
                <td>
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" class="img-circle elevation-2" style="width: 35px; height: 35px; object-fit: cover;" alt="User Image">
                    @else
                        <img src="{{ asset('assets/dist/img/MMOLOGO.png') }}" class="img-circle elevation-2" style="width: 35px; height: 35px; object-fit: cover;" alt="Default Image">
                    @endif
                </td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->role === 'owner')
                        <span class="badge badge-warning">Owner</span>
                    @elseif($user->role === 'admin')
                        <span class="badge badge-danger">Admin</span>
                    @elseif($user->role === 'staff')
                        <span class="badge badge-primary">Staff</span>
                    @elseif($user->role === 'inspector')
                        <span class="badge badge-success">Inspector</span>
                    @elseif($user->role === 'accountant')
                        <span class="badge badge-info">Accountant</span>
                    @else
                        <span class="badge badge-secondary">{{ ucfirst($user->role) }}</span>
                    @endif
                </td>
                <td>
                    @if($user->status === 'active')
                        <span class="badge badge-success">Active</span>
                    @elseif($user->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <button class="btn btn-sm btn-info edit-user-btn" 
                            data-toggle="modal" 
                            data-target="#editUserModal"
                            data-id="{{ $user->id }}" 
                            data-name="{{ $user->name }}" 
                            data-email="{{ $user->email }}" 
                            data-role="{{ $user->role_id }}" 
                            data-role-slug="{{ $user->role }}"
                            data-status="{{ $user->status }}"
                            data-avatar="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : asset('assets/dist/img/MMOLOGO.png') }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    @if($user->id !== auth()->id())
                        <button type="button" class="btn btn-sm btn-danger delete-user-btn" 
                                data-toggle="modal" 
                                data-target="#deleteUserModal"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No users found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="card-footer clearfix">
    <x-pagination :data="$users" />
</div>
