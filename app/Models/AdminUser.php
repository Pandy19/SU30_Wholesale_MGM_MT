<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    protected $table = 'admin_users';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name','email','password','phone','avatar_path','status',
        'email_verified_at','last_login_at','remember_token'
    ];

    protected $hidden = ['password', 'remember_token'];

    // Many-to-many roles
    public function roles()
    {
        return $this->belongsToMany(
            AdminRole::class,
            'admin_user_roles',
            'user_id',
            'role_id'
        );
    }

    // Pivot rows
    public function userRoles()
    {
        return $this->hasMany(AdminUserRole::class, 'user_id', 'id');
    }

    // Assigned roles by this admin
    public function assignedUserRoles()
    {
        return $this->hasMany(AdminUserRole::class, 'assigned_by', 'id');
    }

    public function loginLogs()
    {
        return $this->hasMany(AdminLoginLog::class, 'user_id', 'id');
    }

    public function supplierProfile()
    {
        return $this->hasOne(SupplierProfile::class, 'user_id', 'id');
    }

    public function supplierApprovalLogs()
    {
        return $this->hasMany(SupplierApprovalLog::class, 'action_by', 'id');
    }
}
