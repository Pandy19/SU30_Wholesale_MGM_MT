<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementPurchaseOrderItem extends Model
{
    protected $table = 'procurement_purchase_order_items';
    protected $primaryKey = 'po_item_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'po_id','product_id','supplier_id',
        'sku_snapshot','product_name_snapshot',
        'unit_cost','quantity','line_total'
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

    public function supplier()
    {
        return $this->belongsTo(
            ProcurementSupplier::class,
            'supplier_id',
            'supplier_id'
        );
    }
}
