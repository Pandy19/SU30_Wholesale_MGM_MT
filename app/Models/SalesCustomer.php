<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesCustomer extends Model
{
    protected $table = 'sales_customers';
    protected $primaryKey = 'customer_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'customer_code','name','customer_type','phone','email',
        'credit_limit','outstanding_balance','payment_rule','status'
    ];

    public function orders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_id', 'customer_id');
    }

    public function invoices()
    {
        return $this->hasMany(SalesInvoice::class, 'customer_id', 'customer_id');
    }

    public function payments()
    {
        return $this->hasMany(SalesCustomerPayment::class, 'customer_id', 'customer_id');
    }
}
