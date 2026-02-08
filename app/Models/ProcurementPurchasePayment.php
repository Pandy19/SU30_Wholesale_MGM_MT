<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementPurchasePayment extends Model
{
    protected $table = 'procurement_purchase_payments';
    protected $primaryKey = 'payment_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'po_id','payment_date','payment_method','paid_amount','payment_status'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(
            ProcurementPurchaseOrder::class,
            'po_id',
            'po_id'
        );
    }
}
