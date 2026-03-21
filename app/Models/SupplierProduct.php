<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierProduct extends Model
{
    protected $table = 'supplier_products';
    
    protected $fillable = [
        'supplier_id', 'product_id', 'price', 'available_qty'
    ];
}
