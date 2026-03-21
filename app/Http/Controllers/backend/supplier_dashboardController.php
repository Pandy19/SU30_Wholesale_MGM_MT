<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\GoodsReceivingItem;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class supplier_dashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $supplier = Supplier::where('email', $user->email)->first();

        if (!$supplier) {
            return redirect()->route('dashboard.index')->with('error', 'Supplier profile not found.');
        }

        // Initialize Query
        $query = SupplierProduct::where('supplier_id', $supplier->id)
            ->join('products', 'products.id', '=', 'supplier_products.product_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id');

        // Apply Search Filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('products.name', 'LIKE', "%{$search}%")
                  ->orWhere('products.sku', 'LIKE', "%{$search}%")
                  ->orWhere('brands.name', 'LIKE', "%{$search}%");
            });
        }

        // Apply Status Filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('products.status', $request->status);
        }

        $offers = $query->select(
                'supplier_products.*',
                'products.name as product_name',
                'products.sku',
                'products.image',
                'products.specs',
                'products.description',
                'products.brand_id',
                'products.category_id',
                'products.status as product_status',
                'brands.name as brand_name',
                'categories.name as category_name'
            )
            ->orderBy('supplier_products.created_at', 'DESC')
            ->get();

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('backend.Supplier_Dashboard.index', compact('offers', 'supplier', 'brands', 'categories'));
    }

    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('backend.Supplier_Dashboard.add', compact('brands', 'categories'));
    }

    public function edit($id)
    {
        $offer = SupplierProduct::join('products', 'products.id', '=', 'supplier_products.product_id')
            ->select('supplier_products.*', 'products.name as product_name', 'products.image', 'products.specs', 'products.description', 'products.brand_id', 'products.category_id', 'products.status as product_status')
            ->where('supplier_products.id', $id)
            ->firstOrFail();

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('backend.Supplier_Dashboard.edit', compact('offer', 'brands', 'categories'));
    }

    public function storeOffer(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'brand_id'      => 'required|exists:brands,id',
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|numeric|min:0',
            'available_qty' => 'required|integer|min:0',
            'status'        => 'required|in:available,limited,unavailable',
            'description'   => 'nullable|string',
            'specs'         => 'nullable|string',
            'image'         => 'nullable|image|max:2048', // 2MB Max
        ]);

        $user = Auth::user();
        $supplier = Supplier::where('email', $user->email)->first();

        if (!$supplier) {
            return back()->with('error', 'Supplier not found.');
        }

        // 1. Handle Image Upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // 2. Generate SKU
        $sku = 'PROD-' . strtoupper(Str::random(8));

        // 3. Create Product
        $product = Product::create([
            'sku'         => $sku,
            'name'        => $request->name,
            'brand_id'    => $request->brand_id,
            'category_id' => $request->category_id,
            'image'       => $imagePath,
            'specs'       => $request->specs,
            'status'      => $request->status,
            'description' => $request->description,
        ]);

        // 4. Create Supplier Product Link
        SupplierProduct::create([
            'supplier_id'   => $supplier->id,
            'product_id'    => $product->id,
            'price'         => $request->price,
            'available_qty' => $request->available_qty,
        ]);

        return redirect()->route('Supplier_Dashboard.index')->with('success', 'Product uploaded successfully.');
    }

    public function updateOffer(Request $request, $id)
    {
        $offer = SupplierProduct::findOrFail($id);
        
        $user = Auth::user();
        $supplier = Supplier::where('email', $user->email)->first();
        
        if ($offer->supplier_id !== $supplier->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'name'          => 'required|string|max:255',
            'brand_id'      => 'required|exists:brands,id',
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|numeric|min:0',
            'available_qty' => 'required|integer|min:0',
            'status'        => 'required|in:available,limited,unavailable',
            'specs'         => 'nullable|string',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($offer->product_id);

        // Update Product
        $productData = [
            'name'        => $request->name,
            'brand_id'    => $request->brand_id,
            'category_id' => $request->category_id,
            'specs'       => $request->specs,
            'description' => $request->description,
            'status'      => $request->status,
        ];

        if ($request->hasFile('image')) {
            $productData['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($productData);

        // Update Supplier Link
        $offer->update([
            'price'         => $request->price,
            'available_qty' => $request->available_qty,
        ]);

        return redirect()->route('Supplier_Dashboard.index')->with('success', 'Product offer updated successfully.');
    }

    public function deleteOffer($id)
    {
        $offer = SupplierProduct::findOrFail($id);
        
        // Ensure this supplier owns the offer
        $user = Auth::user();
        $supplier = Supplier::where('email', $user->email)->first();
        
        if ($offer->supplier_id !== $supplier->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Optional: Delete the product itself if it was created uniquely for this offer?
        // For now, we just remove the offer/link.
        $offer->delete();

        return back()->with('success', 'Offer removed successfully.');
    }

    public function disputes(Request $request)
    {
        $user = Auth::user();
        $supplier = Supplier::where('email', $user->email)->first();

        if (!$supplier) {
            return redirect()->route('dashboard.index')->with('error', 'Supplier profile not found.');
        }

        $query = GoodsReceivingItem::where('rejected_qty', '>', 0)
            ->whereHas('goodsReceiving.purchaseOrder', function($q) use ($supplier) {
                $q->where('supplier_id', $supplier->id);
            })
            ->with([
                'goodsReceiving.purchaseOrder',
                'goodsReceiving.approver',
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

        $items = $query->orderBy('id', 'desc')->paginate(10);

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

        return view('backend.Supplier_Dashboard.disputes', compact('items', 'supplier'));
    }

    public function resolveDispute(Request $request, $id)
    {
        $request->validate([
            'resolution' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $item = GoodsReceivingItem::findOrFail($id);
            
            // Security check: ensure item belongs to this supplier
            $user = Auth::user();
            $supplier = Supplier::where('email', $user->email)->first();
            if ($item->goodsReceiving->purchaseOrder->supplier_id !== $supplier->id) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }

            // Update item resolution
            $item->update([
                'resolution_status' => $request->resolution,
                'resolution_notes' => $request->notes,
            ]);

            return redirect()->back()->with('success', 'Dispute resolved: ' . $request->resolution);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
