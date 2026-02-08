<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryGoodsReceivingItem extends Model
{
    protected $table = 'inventory_goods_receiving_items';
    protected $primaryKey = 'grn_item_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'grn_id','po_id','product_id','supplier_id',
        'sku_snapshot','product_name_snapshot',
        'ordered_qty','received_qty','accepted_qty','rejected_qty',
        'unit_cost','line_total','item_status','rejection_reason'
    ];

    public function goodsReceiving()
    {
        return $this->belongsTo(
            InventoryGoodsReceiving::class,
            'grn_id',
            'grn_id'
        );
    }

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

    public function supplier()
    {
        return $this->belongsTo(
            ProcurementSupplier::class,
            'supplier_id',
            'supplier_id'
        );
    }
}
