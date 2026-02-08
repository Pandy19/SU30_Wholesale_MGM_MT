<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryGoodsReceiving extends Model
{
    protected $table = 'inventory_goods_receiving';
    protected $primaryKey = 'grn_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'po_id','supplier_id','received_date','received_by',
        'receiving_status','remarks'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(
            ProcurementPurchaseOrder::class,
            'po_id',
            'po_id'
        );
    }

    public function supplier()
    {
        return $this->belongsTo(
            ProcurementSupplier::class,
            'supplier_id',
            'supplier_id'
        );
    }

    public function items()
    {
        return $this->hasMany(
            InventoryGoodsReceivingItem::class,
            'grn_id',
            'grn_id'
        );
    }
}
