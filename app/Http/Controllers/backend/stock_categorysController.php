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

        // Fetch categories that have products with stock matching filters
        $categoriesQuery = Category::query();
        
        if ($categoryId) {
            $categoriesQuery->where('id', $categoryId);
        }

        // Only show categories that have products in stock matching filters
        $categoriesQuery->whereHas('brands.products', function($q) use ($search, $brandId, $status) {
            if ($search) {
                $q->where(function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%");
                });
            }
            if ($brandId) {
                $q->where('brand_id', $brandId);
            }
            // Status filtering (Normal vs Low Stock)
            $q->whereHas('stocks', function($sq) use ($status) {
                if ($status == 'low_stock') {
                    $sq->where('quantity', '<', 5);
                } elseif ($status == 'normal') {
                    $sq->where('quantity', '>=', 5);
                }
            });
        });

        $perPage = $request->input('per_page', 10);
        $categories = $categoriesQuery->orderBy('name')->paginate($perPage);

        // Map data for display
        $categories->getCollection()->transform(function($category) use ($search, $brandId, $status) {
            $brands = Brand::where('category_id', $category->id)
                ->whereHas('products', function($q) use ($search, $brandId, $status) {
                    if ($search) {
                        $q->where(function($sq) use ($search) {
                            $sq->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%");
                        });
                    }
                    if ($brandId) {
                        $q->where('brand_id', $brandId);
                    }
                    $q->whereHas('stocks', function($sq) use ($status) {
                        if ($status == 'low_stock') {
                            $sq->where('quantity', '<', 5);
                        } elseif ($status == 'normal') {
                            $sq->where('quantity', '>=', 5);
                        }
                    });
                })->get()->map(function($brand) use ($category, $search, $status) {
                    $products = Product::where('brand_id', $brand->id)
                        ->where('category_id', $category->id)
                        ->whereHas('stocks', function($q) use ($status) {
                            if ($status == 'low_stock') {
                                $q->where('quantity', '<', 5);
                            } elseif ($status == 'normal') {
                                $q->where('quantity', '>=', 5);
                            }
                        })
                        ->with(['stocks.location.category', 'stocks.location.brand'])
                        ->when($search, function($q) use ($search) {
                            $q->where(function($sq) use ($search) {
                                $sq->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%");
                            });
                        })
                        ->get();
                
                $brand->products = $products;
                $brand->total_qty = $products->sum(function($p) { return $p->stocks->sum('quantity'); });
                $brand->has_low_stock = $products->contains(function($p) { return $p->stocks->sum('quantity') < 5; });
                return $brand;
            });

            $category->brands_stock = $brands;
            $category->total_qty = $brands->sum('total_qty');
            return $category;
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
