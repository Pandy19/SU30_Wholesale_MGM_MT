<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementPurchaseOrderSupplierSwitchLog extends Model
{
    protected $table = 'procurement_purchase_order_supplier_switch_logs';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = null; // table uses switched_at, not created_at
    const UPDATED_AT = null;

    protected $fillable = [
        'po_id','product_id','old_supplier_id','new_supplier_id',
        'old_unit_cost','new_unit_cost','switched_by','switched_at'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(
            ProcurementPurchaseOrder::class,
            'po_id',
            'po_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(
            InventoryProduct::class,
            'product_id',
            'product_id'
        );
    }

    public function oldSupplier()
    {
        return $this->belongsTo(
            ProcurementSupplier::class,
            'old_supplier_id',
            'supplier_id'
        );
    }

    public function newSupplier()
    {
        return $this->belongsTo(
            ProcurementSupplier::class,
            'new_supplier_id',
            'supplier_id'
        );
    }

    public function switchedBy()
    {
        return $this->belongsTo(AdminUser::class, 'switched_by', 'id');
    }
}
