<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id', 'stock_location_id', 'type', 'quantity', 
        'balance_after', 'reference', 'user_id', 'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function location()
    {
        return $this->belongsTo(StockLocation::class, 'stock_location_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
