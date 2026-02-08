<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfitSalesStockMap extends Model
{
    protected $table = 'profit_sales_stock_map';
    protected $primaryKey = 'sales_stock_map_id';
    public $incrementing = true;
    protected $keyType = 'int';

    const UPDATED_AT = null;

    protected $fillable = [
        'sales_invoice_item_id','stock_batch_id','quantity_used',
        'unit_cost','cost_total','created_at'
    ];

    public function stockBatch()
    {
        return $this->belongsTo(ProfitStockBatch::class, 'stock_batch_id', 'stock_batch_id');
    }
}
