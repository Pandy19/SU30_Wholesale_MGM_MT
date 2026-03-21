<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number', 'supplier_id', 'order_date', 'total_amount', 'status', 'payment_status',
        'payment_method', 'payment_term', 'due_date', 'remarks'
    ];

    protected $casts = [
        'order_date' => 'date',
        'due_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function goodsReceivings()
    {
        return $this->hasMany(GoodsReceiving::class);
    }
}
