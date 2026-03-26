<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class sales_orderController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        
        // Only show products that have actual stock in the stocks table
        $query = Product::with(['category', 'brand', 'stocks'])
            ->whereIn('status', ['available', 'limited'])
            ->whereHas('stocks', function($q) {
                $q->where('quantity', '>', 0);
            });

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        $products = $query->get()->map(function($product) {
            $product->total_stock = $product->stocks->sum('quantity');
            $product->display_price = $product->selling_price;
            
            // Get the highest purchase cost for this product across all POs
            $product->max_unit_cost = \App\Models\PurchaseOrderItem::where('product_id', $product->id)->max('unit_cost') ?? 0;
            
            // B2B base price is initially the same as selling price, discounts applied dynamically in JS
            $product->b2b_price = $product->selling_price; 
            return $product;
        })->filter(function($product) {
            return $product->total_stock > 0;
        });

        return view('backend.sales_order.index', compact('customers', 'categories', 'brands', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required',
            'payment_status' => 'required',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'payment_note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $orderNumber = 'SO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $salesOrder = SalesOrder::create([
                'order_number' => $orderNumber,
                'customer_id' => $request->customer_id,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'order_date' => now(),
                'due_date' => $request->due_date,
                'total_amount' => 0, // Will update after items
                'paid_amount' => $request->paid_amount ?? 0,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'payment_note' => $request->payment_note,
                'created_by' => Auth::id(),
            ]);

            $totalAmount = 0;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $customer = Customer::find($request->customer_id);
                $qty = $item['quantity'];

                // Get max cost to determine discount tier
                $maxCost = \App\Models\PurchaseOrderItem::where('product_id', $product->id)->max('unit_cost') ?? 0;
                
                $unitPrice = $product->selling_price;
                $discountPercent = 0;

                if ($customer->type == 'B2B') {
                    if ($maxCost < 1000) {
                        if ($qty >= 100) $discountPercent = 5;
                        else if ($qty >= 50) $discountPercent = 2;
                    } else { // Cost >= 1000
                        if ($qty >= 100) $discountPercent = 9;
                        else if ($qty >= 50) $discountPercent = 4;
                    }
                }
                
                if ($discountPercent > 0) {
                    $unitPrice = $unitPrice * (1 - ($discountPercent / 100));
                }

                $lineTotal = $unitPrice * $qty;
                
                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ]);

                $totalAmount += $lineTotal;

                // --- DEDUCT STOCK & RECORD MOVEMENT ---
                $remainingToDeduct = $item['quantity'];
                
                // Get all stock locations for this product that have stock, ordered by FIFO (created_at)
                $productStocks = Stock::where('product_id', $item['product_id'])
                    ->where('quantity', '>', 0)
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($productStocks as $stockRecord) {
                    if ($remainingToDeduct <= 0) break;

                    $deductFromThis = min($stockRecord->quantity, $remainingToDeduct);
                    
                    // Deduct from stock table
                    $stockRecord->decrement('quantity', $deductFromThis);
                    $remainingToDeduct -= $deductFromThis;

                    // Record Stock Movement (Ledger)
                    StockMovement::create([
                        'product_id' => $item['product_id'],
                        'stock_location_id' => $stockRecord->stock_location_id,
                        'type' => 'Stock Out',
                        'quantity' => -$deductFromThis, // Negative for Stock Out
                        'balance_after' => Stock::where('product_id', $item['product_id'])->sum('quantity'),
                        'reference' => $orderNumber,
                        'user_id' => Auth::id(),
                        'notes' => 'Sale to customer: ' . $customer->name
                    ]);
                }

                if ($remainingToDeduct > 0) {
                    throw new \Exception("Insufficient stock for product: " . $product->name);
                }
            }

            $salesOrder->update(['total_amount' => $totalAmount]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Order created successfully', 'order_id' => $salesOrder->id]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function confirm($id)
    {
        $order = SalesOrder::with(['customer', 'items.product'])->findOrFail($id);
        return view('backend.sales_order.confirm_sale', compact('order'));
    }
}
