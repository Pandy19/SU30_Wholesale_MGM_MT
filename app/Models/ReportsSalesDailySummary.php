<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportsSalesDailySummary extends Model
{
    protected $table = 'reports_sales_daily_summary';
    protected $primaryKey = 'report_date';
    public $incrementing = false;
    protected $keyType = 'string';

    const UPDATED_AT = null;

    protected $fillable = [
        'report_date','total_sales','total_orders','b2b_sales','b2c_sales','total_profit','created_at'
    ];
}
