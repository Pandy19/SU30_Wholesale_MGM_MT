<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesCustomerPayment extends Model
{
    protected $table = 'sales_customer_payments';
    protected $primaryKey = 'customer_payment_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'payment_code','sales_invoice_id','customer_id',
        'payment_amount','payment_method','payment_date',
        'reference_note','received_by'
    ];

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id', 'sales_invoice_id');
    }

    public function customer()
    {
        return $this->belongsTo(SalesCustomer::class, 'customer_id', 'customer_id');
    }
}
