<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStockLedger extends Model
{
    protected $table = 'inventory_stock_ledger';
    protected $primaryKey = 'ledger_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ledger_date','product_id','sku_snapshot','product_name_snapshot',
        'action_type','qty_in','qty_out','balance_qty',
        'reference_no','performed_by','note'
    ];

    public function product()
    {
        return $this->belongsTo(
            InventoryProduct::class,
            'product_id',
            'product_id'
        );
    }
}
