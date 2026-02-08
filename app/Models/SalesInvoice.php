<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    protected $table = 'sales_invoices';
    protected $primaryKey = 'sales_invoice_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'sales_invoice_code','sales_order_id','customer_id','customer_type',
        'invoice_date','due_date','subtotal_amount','tax_amount','total_amount',
        'paid_amount','balance_amount','payment_status','created_by'
    ];

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'sales_order_id');
    }

    public function customer()
    {
        return $this->belongsTo(SalesCustomer::class, 'customer_id', 'customer_id');
    }

    public function payments()
    {
        return $this->hasMany(SalesCustomerPayment::class, 'sales_invoice_id', 'sales_invoice_id');
    }
}
