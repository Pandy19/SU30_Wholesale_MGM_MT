<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    protected $table = 'admin_roles';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name','label','description','status'];

    public function users()
    {
        return $this->belongsToMany(
            AdminUser::class,
            'admin_user_roles',
            'role_id',
            'user_id'
        );
    }

    public function userRoles()
    {
        return $this->hasMany(AdminUserRole::class, 'role_id', 'id');
    }
}
