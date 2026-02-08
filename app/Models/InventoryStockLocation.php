<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStockLocation extends Model
{
    protected $table = 'inventory_stock_locations';
    protected $primaryKey = 'location_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'location_name','location_code','category_id','status'
    ];

    public function category()
    {
        return $this->belongsTo(
            ProcurementCategory::class,
            'category_id',
            'category_id'
        );
    }

    public function stockRows()
    {
        return $this->hasMany(InventoryStock::class, 'location_id', 'location_id');
    }
}
