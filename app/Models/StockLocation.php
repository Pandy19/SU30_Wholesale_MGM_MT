<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLocation extends Model
{
    protected $fillable = ['name', 'code', 'category_id', 'brand_id', 'max_capacity', 'current_product_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'current_product_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
