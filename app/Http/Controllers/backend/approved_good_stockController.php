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

use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;

class approved_good_stockController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::where('status', 'active')->orderBy('name')->get();
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $suppliers = Supplier::where('status', 'active')->orderBy('company_name')->get();

        // Fetch items and group them by product_id to merge different supplier prices/POs
        $query = GoodsReceivingItem::whereColumn('accepted_qty', '>', 'stocked_qty')
            ->where('is_stocked', false)
            ->with([
                'goodsReceiving.purchaseOrder.supplier',
                'product.brand',
                'product.category'
            ]);

        // Filters...
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

        // Fetch all matching records and group them in PHP to handle complex aggregation
        $rawItems = $query->get();
        
        $items = $rawItems->groupBy('product_id')->map(function($group) {
            $first = $group->first();
            $product = $first->product;
            
            // Sum up pending quantities
            $total_pending = $group->sum(function($item) {
                return $item->accepted_qty - $item->stocked_qty;
            });
            
            // Aggregate IDs for processing
            $item_ids = $group->pluck('id')->implode(',');
            
            // Unique PO numbers and Suppliers
            $po_numbers = $group->map(fn($i) => $i->goodsReceiving->purchaseOrder->po_number ?? 'N/A')->unique()->implode(', ');
            $supplier_names = $group->map(fn($i) => $i->goodsReceiving->purchaseOrder->supplier->company_name ?? 'N/A')->unique()->implode(', ');

            // Highest Unit Cost across ALL POs for this product
            $max_unit_cost = PurchaseOrderItem::where('product_id', $product->id)->max('unit_cost') ?? 0;

            return (object)[
                'id' => $first->id, // reference ID
                'item_ids' => $item_ids,
                'product' => $product,
                'pending_qty' => $total_pending,
                'po_numbers' => $po_numbers,
                'supplier_names' => $supplier_names,
                'max_unit_cost' => $max_unit_cost,
                'is_stocked' => false
            ];
        })->values();

        // Manual pagination for grouped results
        $page = $request->get('page', 1);
        $perPage = 10;
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $locations = StockLocation::with(['category', 'brand', 'product'])->get()->map(function($loc) {
            $currentQty = \App\Models\Stock::where('stock_location_id', $loc->id)->sum('quantity');
            $loc->remaining_space = max(0, $loc->max_capacity - $currentQty);
            $loc->current_qty = $currentQty;
            return $loc;
        });

        return view('backend.approved_good_stock.index', [
            'items' => $paginatedItems,
            'locations' => $locations,
            'brands' => $brands,
            'categories' => $categories,
            'suppliers' => $suppliers
        ]);
    }

    public function addToStock(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|string', // Comma separated IDs
            'location_id' => 'required|exists:stock_locations,id',
            'quantity' => 'required|integer|min:1',
            'selling_price' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $ids = explode(',', $request->item_ids);
            $location = StockLocation::findOrFail($request->location_id);
            $totalToAdd = $request->quantity;
            $sellingPrice = $request->selling_price;

            // Get the product_id from the first item
            $firstItem = GoodsReceivingItem::findOrFail($ids[0]);
            $productId = $firstItem->product_id;

            // Update product selling price
            $firstItem->product->update(['selling_price' => $sellingPrice]);

            // --- SHELF RULES ---
            if ($location->brand_id && $location->brand_id != $firstItem->product->brand_id) {
                $shelfBrand = \App\Models\Brand::find($location->brand_id);
                $productBrand = \App\Models\Brand::find($firstItem->product->brand_id);
                throw new \Exception("Shelf '{$location->name}' is reserved for brand '{$shelfBrand->name}'. Product '{$firstItem->product->name}' is from brand '{$productBrand->name}'.");
            }

            // Check Capacity
            $currentShelfQty = \App\Models\Stock::where('stock_location_id', $location->id)->sum('quantity');
            if (($currentShelfQty + $totalToAdd) > $location->max_capacity) {
                $spaceLeft = $location->max_capacity - $currentShelfQty;
                throw new \Exception("Shelf '{$location->name}' only has space for {$spaceLeft} units.");
            }

            // Distribute quantity among aggregated items
            $remaining = $totalToAdd;
            foreach ($ids as $id) {
                if ($remaining <= 0) break;

                $item = GoodsReceivingItem::find($id);
                $itemPending = $item->accepted_qty - $item->stocked_qty;
                
                if ($itemPending <= 0) continue;

                $take = min($remaining, $itemPending);
                
                $item->increment('stocked_qty', $take);
                if ($item->stocked_qty >= $item->accepted_qty) {
                    $item->update(['is_stocked' => true]);
                }
                
                $remaining -= $take;
            }

            // 3. Update Stock record
            $stock = \App\Models\Stock::where('product_id', $productId)
                                     ->where('stock_location_id', $location->id)
                                     ->first();

            if ($stock) {
                $stock->increment('quantity', $totalToAdd);
            } else {
                \App\Models\Stock::create([
                    'product_id' => $productId,
                    'stock_location_id' => $location->id,
                    'quantity' => $totalToAdd,
                    'average_cost' => PurchaseOrderItem::where('product_id', $productId)->max('unit_cost') ?? 0
                ]);
            }

            // Update location
            $location->update(['current_product_id' => $productId]);

            // Record Stock Movement
            StockMovement::create([
                'product_id' => $productId,
                'stock_location_id' => $location->id,
                'type' => 'Stock In',
                'quantity' => $totalToAdd,
                'balance_after' => \App\Models\Stock::where('product_id', $productId)->sum('quantity'),
                'reference' => 'AGG-STOCK-IN',
                'user_id' => Auth::id(),
                'notes' => $request->notes ?? 'Aggregated goods added to stock'
            ]);
            
            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'Stock successfully updated.',
                'qty' => $totalToAdd,
                'product_name' => $firstItem->product->name
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
