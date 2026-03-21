<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesOrderItem;
use App\Models\SalesOrder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class profit_reportController extends Controller
{
    public function index(Request $request)
    {
        // Base Query for Items
        $itemQuery = SalesOrderItem::query()
            ->join('products', 'sales_order_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->join('sales_orders', 'sales_order_items.sales_order_id', '=', 'sales_orders.id');

        // Apply Filters
        if ($request->filled('start_date')) {
            $itemQuery->whereDate('sales_orders.order_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $itemQuery->whereDate('sales_orders.order_date', '<=', $request->end_date);
        }
        if ($request->filled('category_id')) {
            $itemQuery->where('products.category_id', $request->category_id);
        }
        if ($request->filled('brand_id')) {
            $itemQuery->where('products.brand_id', $request->brand_id);
        }

        // 1. Detailed Table Data
        $profit_details = (clone $itemQuery)
            ->select(
                'products.name as product_name',
                'categories.name as category_name',
                'brands.name as brand_name',
                DB::raw('SUM(sales_order_items.quantity) as total_qty'),
                DB::raw('SUM(sales_order_items.line_total) as total_revenue'),
                DB::raw('AVG(sales_order_items.unit_price) as avg_price'),
                DB::raw('(SELECT average_cost FROM stocks WHERE product_id = products.id LIMIT 1) as average_cost')
            )
            ->groupBy('products.id', 'products.name', 'categories.name', 'brands.name')
            ->get()
            ->map(function($item) {
                $cost_per_unit = ($item->average_cost > 0) ? (float)$item->average_cost : ((float)$item->avg_price * 0.7);
                $item->total_cost = $cost_per_unit * $item->total_qty;
                $item->total_profit = (float)$item->total_revenue - $item->total_cost;
                $item->margin_pct = $item->total_revenue > 0 ? ($item->total_profit / (float)$item->total_revenue) * 100 : 0;
                $item->cost_per_unit = $cost_per_unit;
                return $item;
            });

        // 2. Summary Totals
        $totalRevenue = $profit_details->sum('total_revenue');
        $totalCost = $profit_details->sum('total_cost');
        $totalProfit = $totalRevenue - $totalCost;
        $avgMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;

        // 3. Category-wise Profit (For Pie/Doughnut Chart)
        $categoryProfit = $profit_details->groupBy('category_name')->map(function($group) {
            return $group->sum('total_profit');
        });

        // 4. Monthly Trend (Respecting Date Filters)
        $monthlyTrend = (clone $itemQuery)
            ->select(
                DB::raw("DATE_FORMAT(sales_orders.order_date, '%b %Y') as month"),
                DB::raw("SUM(sales_order_items.line_total) as revenue"),
                DB::raw("DATE_FORMAT(sales_orders.order_date, '%Y-%m') as sort_key")
            )
            ->groupBy('month', 'sort_key')
            ->orderBy('sort_key', 'asc')
            ->get()
            ->map(function($m) {
                $m->profit = $m->revenue * 0.25; // Estimated monthly margin
                return $m;
            });

        // 5. Lookups for filters
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('backend.profit_report.index', compact(
            'profit_details', 'totalRevenue', 'totalCost', 'totalProfit', 'avgMargin',
            'categoryProfit', 'monthlyTrend', 'categories', 'brands'
        ));
    }
}
