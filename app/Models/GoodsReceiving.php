<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiving extends Model
{
    protected $fillable = [
        'purchase_order_id', 'received_date', 'approved_by', 'status', 'remarks'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items()
    {
        return $this->hasMany(GoodsReceivingItem::class);
    }
}
