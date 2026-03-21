<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class product_managementController extends Controller
{
    public function index(Request $request)
    {
        $category_id = $request->query('category');
        $brand_id    = $request->query('brand');
        $search      = $request->query('search');

        $productsQuery = DB::table('products as p')
            ->leftJoin('supplier_products as sp', 'sp.product_id', '=', 'p.id')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->leftJoin('brands as b', 'p.brand_id', '=', 'b.id')
            ->select(
                DB::raw('MIN(p.id) as product_id'), // Use MIN ID as representative
                'p.name as product_name',
                DB::raw('MAX(p.sku) as sku'), // Take one SKU
                DB::raw('MAX(p.image) as image_url'),
                DB::raw('MAX(p.specs) as specs_text'),
                DB::raw('MAX(p.description) as description'),
                DB::raw('MAX(p.status) as status'),
                'c.name as category_name',
                'b.name as brand_name',
                DB::raw('COUNT(DISTINCT sp.supplier_id) as supplier_count'),
                DB::raw('MIN(sp.price) as best_price')
            );

        if (!empty($category_id)) {
            $productsQuery->where('p.category_id', $category_id);
        }
        if (!empty($brand_id)) {
            $productsQuery->where('p.brand_id', $brand_id);
        }
        if (!empty($search)) {
            $productsQuery->where(function ($q) use ($search) {
                $q->where('p.name', 'like', "%{$search}%")
                  ->orWhere('p.sku', 'like', "%{$search}%");
            });
        }

        $products = $productsQuery
            ->groupBy(
                'p.name', 'c.name', 'b.name'
            )
            ->orderBy('product_id', 'desc')
            ->get();

        // Format image URLs
        $products->transform(function($p) {
            if ($p->image_url && !filter_var($p->image_url, FILTER_VALIDATE_URL)) {
                $p->image_url = Storage::disk('public')->url($p->image_url);
            } elseif (!$p->image_url) {
                $p->image_url = asset('assets/dist/img/default-150x150.png');
            }
            return $p;
        });

        $categories = Category::where('status', 'active')->get();
        $brands     = Brand::where('status', 'active')->get();

        return view('backend.product_management.index', compact('products', 'categories', 'brands'));
    }

    public function details($id)
    {
        $product = DB::table('products as p')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->leftJoin('brands as b', 'p.brand_id', '=', 'b.id')
            ->select('p.*', 'c.name as category_name', 'b.name as brand_name')
            ->where('p.id', $id)
            ->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Map image and specs for view
        $product->product_name = $product->name;
        $product->specs_text = $product->specs;
        
        // Format image URL
        if ($product->image && !filter_var($product->image, FILTER_VALIDATE_URL)) {
            $product->image_url = Storage::disk('public')->url($product->image);
        } elseif ($product->image) {
            $product->image_url = $product->image;
        } else {
            $product->image_url = asset('assets/dist/img/default-150x150.png');
        }

        // Get all products with the same name and brand to find all suppliers
        $productIds = DB::table('products')
            ->where('name', $product->name)
            ->where('brand_id', $product->brand_id)
            ->pluck('id');

        $suppliers = DB::table('supplier_products as sp')
            ->join('suppliers as s', 's.id', '=', 'sp.supplier_id')
            ->whereIn('sp.product_id', $productIds)
            ->select(
                's.id as supplier_id', 's.company_name', 's.code as supplier_code', 's.phone', 's.email',
                'sp.price as cost_price', 'sp.available_qty', 'sp.product_id',
                's.lead_time_days as lead_time', 's.status'
            )
            ->orderBy('sp.price', 'asc')
            ->get();

        // Flag the top 2 best prices
        $suppliers->transform(function ($s, $index) {
            $s->is_best_price = ($index < 2);
            return $s;
        });

        $priceHistory = DB::table('purchase_order_items as poi')
            ->join('purchase_orders as po', 'po.id', '=', 'poi.purchase_order_id')
            ->join('suppliers as s', 's.id', '=', 'po.supplier_id')
            ->whereIn('poi.product_id', $productIds)
            ->select(
                's.company_name as supplier_name',
                'poi.unit_cost as purchase_price',
                DB::raw('DATE(poi.created_at) as purchase_date'),
                DB::raw('DATE(poi.updated_at) as last_updated')
            )
            ->orderBy('poi.created_at', 'desc')
            ->get()
            ->unique('supplier_name')
            ->take(2)
            ->values();

        return response()->json([
            'product'       => $product,
            'suppliers'     => $suppliers,
            'price_history' => $priceHistory
        ]);
    }

    public function getCart()
    {
        $cart = session()->get('purchase_cart', []);
        
        // Group by supplier
        $grouped = [];
        foreach ($cart as $id => $item) {
            // Format image URL
            if ($item['image'] && !filter_var($item['image'], FILTER_VALIDATE_URL)) {
                $item['image'] = Storage::disk('public')->url($item['image']);
            } elseif (!$item['image']) {
                $item['image'] = asset('assets/dist/img/default-150x150.png');
            }

            $grouped[$item['supplier_id']]['supplier_name'] = $item['supplier_name'];
            $grouped[$item['supplier_id']]['supplier_code'] = $item['supplier_code'] ?? 'N/A';
            $grouped[$item['supplier_id']]['items'][] = array_merge(['id' => $id], $item);
        }

        return response()->json([
            'cart' => $cart,
            'grouped' => $grouped,
            'total_qty' => array_sum(array_column($cart, 'qty')),
            'grand_total' => array_sum(array_map(function($item) {
                return $item['qty'] * $item['price'];
            }, $cart))
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'supplier_id' => 'required',
            'price' => 'required|numeric',
            'qty' => 'required|integer|min:1'
        ]);

        $product = DB::table('products')->where('id', $request->product_id)->first();
        $supplier = DB::table('suppliers')->where('id', $request->supplier_id)->first();
        $supplier_product = DB::table('supplier_products')
            ->where('product_id', $request->product_id)
            ->where('supplier_id', $request->supplier_id)
            ->first();

        if (!$product || !$supplier) {
            return response()->json(['message' => 'Product or Supplier not found'], 404);
        }

        $cart = session()->get('purchase_cart', []);
        $cartKey = $request->product_id . '_' . $request->supplier_id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $request->qty;
        } else {
            // Format image URL for session
            $imageUrl = $product->image;
            if ($product->image && !filter_var($product->image, FILTER_VALIDATE_URL)) {
                $imageUrl = Storage::disk('public')->url($product->image);
            } elseif (!$product->image) {
                $imageUrl = asset('assets/dist/img/default-150x150.png');
            }

            $cart[$cartKey] = [
                'product_id' => $request->product_id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'image' => $imageUrl,
                'supplier_id' => $request->supplier_id,
                'supplier_name' => $supplier->company_name,
                'supplier_code' => $supplier->code,
                'price' => $request->price,
                'qty' => $request->qty,
                'available_qty' => $supplier_product->available_qty ?? 0,
                'description' => $product->description ?? ''
            ];
        }

        session()->put('purchase_cart', $cart);

        return response()->json(['message' => 'Added to cart successfully', 'cart' => $cart]);
    }

    public function removeFromCart($key)
    {
        $cart = session()->get('purchase_cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('purchase_cart', $cart);
        }
        return response()->json(['message' => 'Removed from cart', 'cart' => $cart]);
    }

    public function updateCartQty(Request $request)
    {
        $cart = session()->get('purchase_cart', []);
        if (isset($cart[$request->key])) {
            $cart[$request->key]['qty'] = $request->qty;
            session()->put('purchase_cart', $cart);
        }
        return response()->json(['message' => 'Quantity updated', 'cart' => $cart]);
    }
}
