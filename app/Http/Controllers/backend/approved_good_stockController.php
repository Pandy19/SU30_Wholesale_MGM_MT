<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\GoodsReceivingItem;
use App\Models\StockLocation;
use App\Models\PurchaseOrderItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class approved_good_stockController extends Controller
{
    public function index(Request $request)
    {
        // Fetch items where accepted_qty > stocked_qty
        $query = GoodsReceivingItem::whereColumn('accepted_qty', '>', 'stocked_qty')
            ->where('is_stocked', false)
            ->with([
                'goodsReceiving.purchaseOrder.supplier',
                'product.brand',
                'product.category'
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('brand_id', $request->brand);
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        if ($request->filled('supplier')) {
            $query->whereHas('goodsReceiving.purchaseOrder', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier);
            });
        }

        if ($request->filled('storage_location')) {
            $location = StockLocation::find($request->storage_location);
            if ($location) {
                $query->whereHas('product', function ($q) use ($location) {
                    $q->where('brand_id', $location->brand_id)
                      ->where('category_id', $location->category_id);
                });
            }
        }

        $items = $query->orderBy('id', 'desc')->paginate(10);
        
        // Fetch locations with their associations
        $locations = StockLocation::with(['category', 'brand', 'product'])->get()->map(function($loc) {
            $currentQty = \App\Models\Stock::where('stock_location_id', $loc->id)->sum('quantity');
            $loc->remaining_space = max(0, $loc->max_capacity - $currentQty);
            $loc->current_qty = $currentQty;
            return $loc;
        });

        // Calculate total cost for display
        foreach ($items as $item) {
            $item->pending_qty = $item->accepted_qty - $item->stocked_qty;
            $unit_cost = 0;
            if ($item->goodsReceiving && $item->goodsReceiving->purchase_order_id) {
                $poItem = PurchaseOrderItem::where('purchase_order_id', $item->goodsReceiving->purchase_order_id)
                                            ->where('product_id', $item->product_id)
                                            ->first();
                $unit_cost = $poItem ? $poItem->unit_cost : 0;
            }
            $item->unit_cost = $unit_cost;
            $item->total_value = $item->pending_qty * $unit_cost;
        }

        return view('backend.approved_good_stock.index', compact('items', 'locations'));
    }

    public function addToStock(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:goods_receiving_items,id',
            'location_id' => 'required|exists:stock_locations,id',
            'quantity' => 'required|integer|min:1',
            'selling_price' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $item = GoodsReceivingItem::findOrFail($request->item_id);
            $location = StockLocation::findOrFail($request->location_id);
            $addQty = $request->quantity;
            $sellingPrice = $request->selling_price;

            $pendingQty = $item->accepted_qty - $item->stocked_qty;

            if ($item->is_stocked || $pendingQty <= 0) {
                throw new \Exception("This item has already been fully added to stock.");
            }

            if ($addQty > $pendingQty) {
                throw new \Exception("Cannot add more than pending quantity ({$pendingQty}).");
            }

            // Update product selling price
            $item->product->update(['selling_price' => $sellingPrice]);

            // --- SHELF RULES ---
            
            // 1. Check if shelf already has a DIFFERENT product
            if ($location->current_product_id && $location->current_product_id != $item->product_id) {
                $otherProduct = \App\Models\Product::find($location->current_product_id);
                throw new \Exception("Shelf '{$location->name}' is reserved for '{$otherProduct->name}'. You cannot mix different products on the same shelf.");
            }

            // 2. Check Capacity (Max 50)
            $currentShelfQty = \App\Models\Stock::where('stock_location_id', $location->id)->sum('quantity');
            if (($currentShelfQty + $addQty) > $location->max_capacity) {
                $spaceLeft = $location->max_capacity - $currentShelfQty;
                throw new \Exception("Shelf '{$location->name}' only has space for {$spaceLeft} more units. Total capacity is {$location->max_capacity}.");
            }

            // 3. Find or Create Stock record
            $stock = \App\Models\Stock::where('product_id', $item->product_id)
                                     ->where('stock_location_id', $location->id)
                                     ->first();

            if ($stock) {
                $stock->increment('quantity', $addQty);
            } else {
                \App\Models\Stock::create([
                    'product_id' => $item->product_id,
                    'stock_location_id' => $location->id,
                    'quantity' => $addQty,
                    'average_cost' => PurchaseOrderItem::where('purchase_order_id', $item->goodsReceiving->purchase_order_id)
                                                      ->where('product_id', $item->product_id)
                                                      ->first()->unit_cost ?? 0
                ]);
            }

            // Update location to track current product
            $location->update(['current_product_id' => $item->product_id]);

            // Record Stock Movement (Ledger)
            StockMovement::create([
                'product_id' => $item->product_id,
                'stock_location_id' => $location->id,
                'type' => 'Stock In',
                'quantity' => $addQty,
                'balance_after' => \App\Models\Stock::where('stock_location_id', $location->id)->sum('quantity'),
                'reference' => $item->goodsReceiving->purchaseOrder->po_number ?? 'N/A',
                'user_id' => Auth::id(),
                'notes' => $request->notes ?? 'Approved goods added to stock'
            ]);

            // 4. Update stocked quantity and mark item as "In Stock" if full quantity moved
            $item->increment('stocked_qty', $addQty);
            
            if ($item->stocked_qty >= $item->accepted_qty) {
                $item->update(['is_stocked' => true]);
            }
            
            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'Goods successfully moved to shelf: ' . $location->name,
                'qty' => $addQty,
                'product_name' => $item->product->name
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
