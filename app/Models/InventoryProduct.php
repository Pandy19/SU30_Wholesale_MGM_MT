<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ✅ Import related models (so no class not found)
use App\Models\ProcurementBrand;
use App\Models\ProcurementCategory;
use App\Models\InventoryStock;
use App\Models\InventoryStockLedger;
use App\Models\ProcurementPurchaseOrderItem;
use App\Models\SalesOrderItem;

class InventoryProduct extends Model
{
    protected $table = 'inventory_products';
    protected $primaryKey = 'product_id';

    public $incrementing = true;
    public $timestamps = true;
    protected $keyType = 'int';

    protected $fillable = [
        'sku',
        'name',
        'brand_id',
        'category_id',
        'image_url',
        'specs_text',
        'status',
    ];

    // optional but nice
    protected $casts = [
        'brand_id' => 'integer',
        'category_id' => 'integer',
    ];

    /* ================= RELATIONSHIPS ================= */

    public function brand()
    {
        return $this->belongsTo(ProcurementBrand::class, 'brand_id', 'brand_id');
    }

    public function category()
    {
        return $this->belongsTo(ProcurementCategory::class, 'category_id', 'category_id');
    }

    public function stockRows()
    {
        return $this->hasMany(InventoryStock::class, 'product_id', 'product_id');
    }

    public function ledgerRows()
    {
        return $this->hasMany(InventoryStockLedger::class, 'product_id', 'product_id');
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(ProcurementPurchaseOrderItem::class, 'product_id', 'product_id');
    }

    public function salesOrderItems()
    {
        return $this->hasMany(SalesOrderItem::class, 'product_id', 'product_id');
    }
}