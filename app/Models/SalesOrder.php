<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $table = 'sales_orders';
    protected $primaryKey = 'sales_order_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'sales_order_code','customer_id','customer_type',
        'payment_method','payment_terms','payment_status',
        'subtotal_amount','tax_amount','total_amount',
        'payment_note','created_by','sale_date'
    ];

    public function customer()
    {
        return $this->belongsTo(SalesCustomer::class, 'customer_id', 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class, 'sales_order_id', 'sales_order_id');
    }

    public function invoice()
    {
        return $this->hasOne(SalesInvoice::class, 'sales_order_id', 'sales_order_id');
    }
}
