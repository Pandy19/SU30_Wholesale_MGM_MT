<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUserRole extends Model
{
    protected $table = 'admin_user_roles';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = ['user_id','role_id','assigned_by','assigned_at'];

    public function user()
    {
        return $this->belongsTo(AdminUser::class, 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(AdminRole::class, 'role_id', 'id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(AdminUser::class, 'assigned_by', 'id');
    }
}
