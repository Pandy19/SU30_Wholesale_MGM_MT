<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceivingItem extends Model
{
    protected $fillable = [
        'goods_receiving_id', 'product_id', 'ordered_qty', 'received_qty', 
        'accepted_qty', 'stocked_qty', 'rejected_qty', 'resolution_status', 'resolution_notes', 'is_stocked'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function goodsReceiving()
    {
        return $this->belongsTo(GoodsReceiving::class);
    }
}
