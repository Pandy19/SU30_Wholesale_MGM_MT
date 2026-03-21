<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Stock;
use App\Models\StockLocation;
use Illuminate\Support\Facades\DB;

class product_stock_listController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::whereHas('stocks')
            ->with(['category', 'brand', 'stocks.location', 'suppliers'])
            ->withSum('stocks as total_qty', 'quantity')
            ->withAvg('stocks as avg_cost', 'average_cost');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Brand Filter
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Status Filter (Normal, Low Stock, Out of Stock)
        if ($request->filled('status')) {
            if ($request->status == 'low_stock') {
                $query->having('total_qty', '>', 0)->having('total_qty', '<', 10);
            } elseif ($request->status == 'out_of_stock') {
                $query->having('total_qty', '<=', 0)->orHavingNull('total_qty');
            } elseif ($request->status == 'normal') {
                $query->having('total_qty', '>=', 10);
            }
        }

        // Shelf / Location Filter
        if ($request->filled('location_id')) {
            $query->whereHas('stocks', function($q) use ($request) {
                $q->where('stock_location_id', $request->location_id);
            });
        }

        $products = $query->orderBy('name')->paginate(10);

        // Summaries
        $totalProducts = Product::count();
        $totalUnits = Stock::sum('quantity');
        
        // Low stock count (Qty < 10 but > 0)
        $lowStockCount = Product::withSum('stocks as total_qty', 'quantity')
            ->having('total_qty', '>', 0)
            ->having('total_qty', '<', 10)
            ->count();
        
        // Total Stock Value (Qty * Avg Cost)
        $totalStockValue = Stock::select(DB::raw('SUM(quantity * average_cost) as total'))->first()->total ?? 0;

        // Data for filters
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $locations = StockLocation::orderBy('name')->get();

        return view('backend.product_stock_list.index', compact(
            'products',
            'totalProducts',
            'totalUnits',
            'lowStockCount',
            'totalStockValue',
            'categories',
            'brands',
            'locations'
        ));
    }
}
