<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class stock_categorysController extends Controller
{
    public function index(Request $request)
    {
        // Summary calculations
        $totalCategoriesCount = Category::count();
        $totalStockQty = Stock::sum('quantity');
        
        // Let's define low stock as less than 5 units (example)
        $lowStockItemsCount = Stock::where('quantity', '<', 5)->count();

        // Get filter inputs
        $search = $request->search;
        $brandId = $request->brand_id;
        $categoryId = $request->category_id;
        $status = $request->status;

        // Fetch categories that have products, and brands for those products
        $categoriesQuery = Category::query();
        
        if ($categoryId) {
            $categoriesQuery->where('id', $categoryId);
        }

        $categories = $categoriesQuery->orderBy('name')->get()->map(function($category) use ($search, $brandId, $status) {
            $brands = Brand::whereHas('products', function($q) use ($category, $search, $brandId, $status) {
                $q->where('category_id', $category->id);
                if ($search) {
                    $q->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%");
                }
                if ($brandId) {
                    $q->where('brand_id', $brandId);
                }
                // Status filtering (Normal vs Low Stock)
                if ($status == 'low_stock') {
                    $q->whereHas('stocks', function($sq) { $sq->where('quantity', '<', 5); });
                } elseif ($status == 'normal') {
                    $q->whereHas('stocks', function($sq) { $sq->where('quantity', '>=', 5); });
                }
                $q->whereHas('stocks');
            })->get()->map(function($brand) use ($category, $search, $status) {
                $products = Product::where('brand_id', $brand->id)
                    ->where('category_id', $category->id)
                    ->whereHas('stocks')
                    ->with(['stocks.location.category', 'stocks.location.brand'])
                    ->when($search, function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%");
                    })
                    ->when($status == 'low_stock', function($q) {
                        $q->whereHas('stocks', function($sq) { $sq->where('quantity', '<', 5); });
                    })
                    ->when($status == 'normal', function($q) {
                        $q->whereHas('stocks', function($sq) { $sq->where('quantity', '>=', 5); });
                    })
                    ->get();
                
                $brand->products = $products;
                $brand->total_qty = $products->sum(function($p) { return $p->stocks->sum('quantity'); });
                $brand->has_low_stock = $products->contains(function($p) { return $p->stocks->sum('quantity') < 5; });
                return $brand;
            })->filter(function($brand) {
                return $brand->products->count() > 0;
            });

            $category->brands_stock = $brands;
            $category->total_qty = $brands->sum('total_qty');
            return $category;
        })->filter(function($category) {
            return $category->brands_stock->count() > 0;
        });

        $brands = Brand::orderBy('name')->get();
        $allCategories = Category::orderBy('name')->get();

        return view('backend.stock_categorys.index', compact(
            'categories', 
            'totalCategoriesCount', 
            'totalStockQty', 
            'lowStockItemsCount',
            'brands',
            'allCategories'
        ));
    }
}
