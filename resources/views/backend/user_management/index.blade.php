@extends('backend.layouts.master')

@section('title', 'User Management | Wholesale MGM')

@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Management</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                        <i class="fas fa-plus mr-1"></i> Create New User
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            {{-- Alerts are handled by SweetAlert2 in master layout --}}

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">All System Users</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" id="userSearchInput" name="search" class="form-control float-right" placeholder="Search by name, email or code" value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0" id="userTableContainer">
                    @include('backend.user_management.table')
                </div>
            </div>
        </div>
    </section>
</div>

<!-- DELETE USER MODAL -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteUserForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle text-danger mb-3" style="font-size: 3rem;"></i>
                    <h4>Are you sure?</h4>
                    <p>You are about to delete user: <strong id="deleteUserName"></strong>. This action cannot be undone!</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CREATE USER MODAL -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="createUserModalLabel">Create New System User</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user_management.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @include('backend.user_management.add')
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save User Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT USER MODAL -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="editUserModalLabel">Update User Account</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editUserForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    @include('backend.user_management.edit')
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" id="updateUserBtn" class="btn btn-info text-white">Update Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const currentUserRole = "{{ auth()->user()->role }}";
    
    // AJAX FETCH FUNCTION
    function fetchUsers(targetUrl = null) {
        const url = targetUrl || "{{ route('user_management.index') }}";
        const query = $('#userSearchInput').val();
        const perPage = $('select[name="per_page"]').val() || 10;
        
        let ajaxData = {};
        // If we're not using a specific pagination URL, we need to pass search/perPage
        if (!targetUrl) {
            ajaxData = {
                search: query,
                per_page: perPage,
                page: 1
            };
        } else {
            // If targetUrl is from pagination, it already contains page and potentially search/per_page.
            // However, we want to ensure per_page remains consistent with the current selection
            // if it's not already in the URL.
            if (targetUrl.indexOf('per_page=') === -1) {
                ajaxData.per_page = perPage;
            }
        }

        $.ajax({
            url: url,
            method: 'GET',
            data: ajaxData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(data) {
                $('#userTableContainer').html(data);
                // Maintain focus or cursor position if needed, though usually not for pagination
            },
            error: function(xhr) {
                console.error("Error fetching users:", xhr);
                // Optionally show a toast/alert if it fails
            }
        });
    }

    // SEARCH INPUT
    let searchTimer;
    $('#userSearchInput').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            fetchUsers();
        }, 500);
    });

    // PAGINATION LINKS
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url && url !== '#') {
            fetchUsers(url);
        }
    });

    // ROWS PER PAGE
    $(document).on('change', 'select[name="per_page"]', function(e) {
        e.preventDefault();
        fetchUsers();
    });

    // EDIT MODAL POPULATE
    $(document).on('click', '.edit-user-btn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const email = $(this).data('email');
        const role = $(this).data('role');
        const roleSlug = $(this).data('role-slug');
        const status = $(this).data('status');
        const avatar = $(this).data('avatar');

        // Set Form Action
        $('#editUserForm').attr('action', `/user_management/${id}`);

        // Populate Fields
        $('#edit_name').val(name);
        $('#edit_email').val(email);
        $('#edit_role_id').val(role).trigger('change');
        $('#edit_role_id_hidden').val(role);
        $('#edit_status').val(status);
        $('#avatarPreviewEdit').attr('src', avatar);
        
        // Clear passwords
        $('#edit_password').val('');
        $('#edit_password_confirmation').val('');

        // RESET UI
        $('#edit_name, #edit_email, #edit_password, #edit_password_confirmation').prop('readonly', false);
        $('#edit_role_id, #edit_status').prop('disabled', false);
        $('#avatarInputEdit').prop('disabled', false);
        $('#updateUserBtn').show();
        $('#adminNotice').remove();

        // LOGIC 1: Admin cannot edit other Admins (only Owner can)
        if (roleSlug === 'admin' && currentUserRole === 'admin') {
            $('#edit_name, #edit_email, #edit_password, #edit_password_confirmation').prop('readonly', true);
            $('#edit_role_id, #edit_status').prop('disabled', true);
            $('#avatarInputEdit').prop('disabled', true);
            $('#updateUserBtn').hide();
            
            $('<div id="adminNotice" class="alert alert-warning mt-2"><i class="fas fa-exclamation-triangle mr-1"></i> Admin accounts cannot be modified by other Admins.</div>')
                .prependTo('#editUserModal .modal-body');
        } 
        // LOGIC 2: Role dropdown is read-only ONLY if the target user IS an Admin
        else if (roleSlug === 'admin') {
             $('#edit_role_id').prop('disabled', true);
             $('<div id="adminNotice" class="alert alert-info mt-2"><i class="fas fa-info-circle mr-1"></i> Admin roles are protected and cannot be changed.</div>')
                .prependTo('#editUserModal .modal-body');
        }
        // LOGIC 3: If current user is Admin, they can't assign 'Admin' role to others
        if (currentUserRole === 'admin') {
            // Disable the 'Admin' option in the dropdown for non-admin targets
            $("#edit_role_id option").each(function() {
                if ($(this).text().toLowerCase() === 'admin') {
                    $(this).prop('disabled', true);
                }
            });
        } else {
            // Re-enable for Owner
            $("#edit_role_id option").prop('disabled', false);
        }
    });

    // Update hidden role_id when select changes
    $(document).on('change', '#edit_role_id', function() {
        $('#edit_role_id_hidden').val($(this).val());
    });

    // DELETE MODAL
    $(document).on('click', '.delete-user-btn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        $('#deleteUserForm').attr('action', `/user_management/${id}`);
        $('#deleteUserName').text(name);
    });
});
</script>
@endpush
@endsection
