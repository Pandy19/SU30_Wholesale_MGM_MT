<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProcurementSupplier;
use App\Models\ProcurementCategory;
use App\Models\InventoryProduct;

class ProcurementBrand extends Model
{
    protected $table = 'procurement_brands';
    protected $primaryKey = 'brand_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'category_id',
        'brand_name',
        'brand_slug',
        'logo_path',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(
            ProcurementCategory::class,
            'category_id',
            'category_id'
        );
    }

    public function suppliers()
    {
        return $this->hasMany(
            ProcurementSupplier::class,
            'brand_id',
            'brand_id'
        );
    }

    public function products()
    {
        return $this->hasMany(
            InventoryProduct::class,
            'brand_id',
            'brand_id'
        );
    }
}
