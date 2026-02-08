<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProcurementBrand;
use App\Models\ProcurementSupplier;

class ProcurementCategory extends Model
{
    protected $table = 'procurement_categories';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_code',
        'category_name',
        'description',
        'status',
    ];

    public function brands()
    {
        return $this->hasMany(ProcurementBrand::class, 'category_id', 'category_id');
    }

    public function suppliers()
    {
        return $this->hasMany(ProcurementSupplier::class, 'category_id', 'category_id');
    }
}
