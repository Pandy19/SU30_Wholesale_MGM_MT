<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierApprovalLog extends Model
{
    protected $table = 'supplier_approval_logs';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    const UPDATED_AT = null;

    protected $fillable = [
        'supplier_profile_id','action','action_by','remark','created_at'
    ];

    public function supplierProfile()
    {
        return $this->belongsTo(
            SupplierProfile::class,
            'supplier_profile_id',
            'supplier_profile_id'
        );
    }

    public function actionBy()
    {
        return $this->belongsTo(AdminUser::class, 'action_by', 'id');
    }
}
