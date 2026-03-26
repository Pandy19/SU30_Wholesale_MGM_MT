<!-- AVATAR -->
<div class="form-group text-center mb-3">
    <label class="d-block font-weight-bold mb-2">Profile Avatar</label>
    <div class="avatar-wrapper mx-auto @error('avatar') is-invalid @enderror" style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 3px solid #007bff; position: relative; cursor: pointer;">
        <label for="avatarInputEdit" class="avatar-label mb-0">
            <img src="{{ asset('assets/dist/img/MMOLOGO.png') }}" id="avatarPreviewEdit" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
        </label>
        <input type="file" id="avatarInputEdit" name="avatar" accept="image/*" hidden>
    </div>
    @error('avatar')
        <span class="text-danger small d-block mt-1">{{ $message }}</span>
    @else
        <small class="text-muted d-block mt-2">Click to change (Optional)</small>
    @enderror
</div>

<!-- BASIC INFO -->
<div class="form-row">
    <div class="form-group col-md-6">
        <label>Full Name</label>
        <input type="text" name="name" id="edit_name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter full name" required>
        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
    <div class="form-group col-md-6">
        <label>Email Address</label>
        <input type="email" name="email" id="edit_email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email" required>
        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>Role</label>
        <select id="edit_role_id" class="form-control select2bs4" style="width: 100%;" required>
            <option value="">-- Select Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
        <input type="hidden" name="role_id" id="edit_role_id_hidden">
    </div>
    <div class="form-group col-md-6">
        <label>Status</label>
        <select name="status" id="edit_status" class="form-control" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="pending">Pending Approval</option>
        </select>
    </div>
</div>

<div class="alert alert-info py-2 small">
    <i class="fas fa-info-circle mr-1"></i> Leave password fields <strong>blank</strong> if you don't want to change it.
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>New Password</label>
        <input type="password" name="password" id="edit_password" class="form-control" placeholder="New password">
    </div>
    <div class="form-group col-md-6">
        <label>Confirm New Password</label>
        <input type="password" name="password_confirmation" id="edit_password_confirmation" class="form-control" placeholder="Confirm new password">
    </div>
</div>

<script>
    document.getElementById('avatarInputEdit').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreviewEdit').src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
