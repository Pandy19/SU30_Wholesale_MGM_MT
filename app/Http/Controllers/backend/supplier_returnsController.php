<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\GoodsReceivingItem;
use App\Models\PurchaseOrderItem;
use App\Models\Brand;
use App\Models\Category;

class supplier_returnsController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::where('status', 'active')->get();
        $categories = Category::where('status', 'active')->get();

        $query = GoodsReceivingItem::where('rejected_qty', '>', 0)
            ->with([
                'goodsReceiving.purchaseOrder.supplier',
                'goodsReceiving.approver',
                'product.brand',
                'product.category'
            ]);

        // Server-side Filtering (for initial load and page refresh)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                })->orWhereHas('goodsReceiving.purchaseOrder', function ($q2) use ($search) {
                    $q2->where('po_number', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('brand_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('brand_id', $request->brand_id);
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

        return view('backend.supplier_returns.index', compact('items', 'brands', 'categories'));
    }
}
