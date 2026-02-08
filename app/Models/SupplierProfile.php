<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierProfile extends Model
{
    protected $table = 'supplier_profiles';
    protected $primaryKey = 'supplier_profile_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id','company_name','company_license','license_file',
        'verification_status','verified_by','verified_at','note'
    ];

    public function user()
    {
        return $this->belongsTo(AdminUser::class, 'user_id', 'id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(AdminUser::class, 'verified_by', 'id');
    }

    public function approvalLogs()
    {
        return $this->hasMany(
            SupplierApprovalLog::class,
            'supplier_profile_id',
            'supplier_profile_id'
        );
    }
}
