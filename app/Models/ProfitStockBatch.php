<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfitStockBatch extends Model
{
    protected $table = 'profit_stock_batches';
    protected $primaryKey = 'stock_batch_id';
    public $incrementing = true;
    protected $keyType = 'int';

    const UPDATED_AT = null;

    protected $fillable = [
        'product_id','supplier_id','purchase_order_id',
        'unit_cost','quantity_received','quantity_remaining',
        'received_date','created_at'
    ];

    public function product()
    {
        return $this->belongsTo(InventoryProduct::class, 'product_id', 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo(ProcurementSupplier::class, 'supplier_id', 'supplier_id');
    }
}
