<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStock extends Model
{
    protected $table = 'inventory_stock';
    protected $primaryKey = 'stock_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Your table uses last_updated instead of updated_at
    const UPDATED_AT = 'last_updated';

    protected $fillable = [
        'product_id','location_id','qty_on_hand','avg_cost','last_updated'
    ];

    public function product()
    {
        return $this->belongsTo(
            InventoryProduct::class,
            'product_id',
            'product_id'
        );
    }

    public function location()
    {
        return $this->belongsTo(
            InventoryStockLocation::class,
            'location_id',
            'location_id'
        );
    }
}
