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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class sales_orderController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        
        // Only show products that have actual stock in the stocks table and selling price > 0
        $query = Product::with(['category', 'brand', 'stocks'])
            ->where('status', 'available')
            ->where('selling_price', '>', 0)
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
            // Use selling_price from products table. 
            // If selling_price is 0 or null, we might want to skip or handle it.
            $product->display_price = $product->selling_price;
            // Mock B2B price as 5% less than selling_price
            $product->b2b_price = $product->selling_price * 0.95; 
            return $product;
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
                
                $unitPrice = ($customer->type == 'B2B') ? ($product->selling_price * 0.95) : $product->selling_price;
                $lineTotal = $unitPrice * $item['quantity'];
                
                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ]);

                $totalAmount += $lineTotal;

                // Optional: Deduct stock here or on confirmation
                // For now, we just track the sale
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
