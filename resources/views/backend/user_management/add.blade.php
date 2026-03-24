<!-- AVATAR -->
<div class="form-group text-center mb-3">
    <label class="d-block font-weight-bold mb-2">Profile Avatar</label>
    <div class="avatar-wrapper mx-auto" style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 3px solid #007bff; position: relative; cursor: pointer;">
        <label for="avatarInputAdd" class="avatar-label mb-0">
            <img src="{{ asset('assets/dist/img/MMOLOGO.png') }}" id="avatarPreviewAdd" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
        </label>
        <input type="file" id="avatarInputAdd" name="avatar" accept="image/*" hidden>
    </div>
    <small class="text-muted d-block mt-2">Click to upload (JPG/PNG)</small>
</div>

<!-- BASIC INFO -->
<div class="form-row">
    <div class="form-group col-md-12">
        <label>Role</label>
        <select name="role_id" class="form-control select2bs4" style="width: 100%;" required>
            <option value="">-- Select Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>Full Name</label>
        <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
    </div>
    <div class="form-group col-md-6">
        <label>Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter email" required>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <div class="form-group col-md-6">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
    </div>
</div>

<script>
    document.getElementById('avatarInputAdd').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreviewAdd').src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
