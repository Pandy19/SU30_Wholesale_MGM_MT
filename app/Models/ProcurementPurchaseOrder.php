<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementPurchaseOrder extends Model
{
    protected $table = 'procurement_purchase_orders';
    protected $primaryKey = 'po_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'po_number','supplier_id','order_date','invoice_date',
        'subtotal_amount','tax_amount','total_amount',
        'payment_status','created_by','receiving_status','stock_status'
    ];

    public function supplier()
    {
        return $this->belongsTo(
            ProcurementSupplier::class,
            'supplier_id',
            'supplier_id'
        );
    }

    public function items()
    {
        return $this->hasMany(
            ProcurementPurchaseOrderItem::class,
            'po_id',
            'po_id'
        );
    }

    public function payments()
    {
        return $this->hasMany(
            ProcurementPurchasePayment::class,
            'po_id',
            'po_id'
        );
    }

    public function goodsReceiving()
    {
        return $this->hasOne(
            InventoryGoodsReceiving::class,
            'po_id',
            'po_id'
        );
    }

    public function switchLogs()
    {
        return $this->hasMany(
            ProcurementPurchaseOrderSupplierSwitchLog::class,
            'po_id',
            'po_id'
        );
    }
}
