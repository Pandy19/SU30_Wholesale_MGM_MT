<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_code', 'name', 'type', 'phone', 'email', 'profile_picture', 'address', 'tax_number', 'contact_person', 'payment_terms', 'credit_limit', 'status'
    ];

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }
}
