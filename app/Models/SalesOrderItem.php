<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
    protected $table = 'sales_order_items';
    protected $primaryKey = 'sales_order_item_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'sales_order_id','product_id','sku_snapshot','product_name_snapshot',
        'description_snapshot','qty','unit_price','subtotal'
    ];

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'sales_order_id');
    }

    public function product()
    {
        return $this->belongsTo(InventoryProduct::class, 'product_id', 'product_id');
    }
}
