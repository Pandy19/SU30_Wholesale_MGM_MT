<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\GoodsReceivingItem;
use App\Models\PurchaseOrderItem;

class supplier_returnsController extends Controller
{
    public function index(Request $request)
    {
        $query = GoodsReceivingItem::where('rejected_qty', '>', 0)
            ->with([
                'goodsReceiving.purchaseOrder.supplier',
                'goodsReceiving.approver',
                'product.brand',
                'product.category'
            ]);

        // Basic Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })->orWhereHas('goodsReceiving.purchaseOrder', function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('id', 'desc')->paginate(10);

        // Calculate total value for each rejected item
        foreach ($items as $item) {
            $unit_cost = 0;
            if ($item->goodsReceiving && $item->goodsReceiving->purchase_order_id) {
                $poItem = PurchaseOrderItem::where('purchase_order_id', $item->goodsReceiving->purchase_order_id)
                                            ->where('product_id', $item->product_id)
                                            ->first();
                $unit_cost = $poItem ? $poItem->unit_cost : 0;
            }
            $item->unit_cost = $unit_cost;
            $item->total_value = $item->rejected_qty * $unit_cost;
        }

        return view('backend.supplier_returns.index', compact('items'));
    }
}
