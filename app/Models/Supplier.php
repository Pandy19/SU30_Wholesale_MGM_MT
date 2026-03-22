<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'code', 'company_name', 'contact_person', 'phone', 'email', 'address',
        'payment_term', 'lead_time_days', 'document', 'status', 'brand_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'supplier_products')
                    ->withPivot('price', 'available_qty')
                    ->withTimestamps();
    }
}
