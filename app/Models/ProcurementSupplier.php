<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementSupplier extends Model
{
    protected $table = 'procurement_suppliers';
    protected $primaryKey = 'supplier_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'brand_id','category_id','supplier_code','company_name',
        'contact_person','phone','email','address',
        'payment_term','lead_time_days','document_path','status'
    ];

    public function brand()
    {
        return $this->belongsTo(ProcurementBrand::class, 'brand_id', 'brand_id');
    }

    public function category()
    {
        return $this->belongsTo(ProcurementCategory::class, 'category_id', 'category_id');
    }
}
