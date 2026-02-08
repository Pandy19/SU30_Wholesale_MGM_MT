<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportsProfitProductSummary extends Model
{
    protected $table = 'reports_profit_product_summary';
    protected $primaryKey = 'report_date';
    public $incrementing = false;
    protected $keyType = 'string';

    const UPDATED_AT = null;

    protected $fillable = [
        'report_date','category_id','brand_id','product_id',
        'total_qty_sold','total_cost','total_revenue','total_profit',
        'profit_margin','created_at'
    ];

    public function category()
    {
        return $this->belongsTo(ProcurementCategory::class, 'category_id', 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(ProcurementBrand::class, 'brand_id', 'brand_id');
    }

    public function product()
    {
        return $this->belongsTo(InventoryProduct::class, 'product_id', 'product_id');
    }
}
