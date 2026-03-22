<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Stock;
use App\Models\SalesOrder;
use App\Models\StockMovement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $supplierCount = Supplier::count();
        $productCount = Product::count();
        $lowStockCount = Stock::where('quantity', '<', 10)->count();
        $todaySales = SalesOrder::whereDate('created_at', Carbon::today())->sum('total_amount');
        
        // Fetch different activity types (Last 5 of each to ensure we have enough for 10 total)
        $stockActivities = StockMovement::with(['product', 'user'])->latest()->take(5)->get()->map(function($item) {
            $item->activity_type = 'stock';
            return $item;
        });

        $salesActivities = SalesOrder::with('customer')->latest()->take(5)->get()->map(function($item) {
            $item->activity_type = 'sale';
            return $item;
        });

        $purchaseActivities = \App\Models\PurchaseOrder::with('supplier')->latest()->take(5)->get()->map(function($item) {
            $item->activity_type = 'purchase';
            $item->po_number = $item->order_number; // Map the order_number to po_number for the view
            return $item;
        });

        // Merge and sort by created_at, showing only the top 5
        $unifiedActivities = $stockActivities->concat($salesActivities)->concat($purchaseActivities)
            ->sortByDesc('created_at')
            ->take(5);
        
        $newSuppliers = Supplier::latest()->take(5)->get();
        $latestOrders = SalesOrder::with('customer')->latest()->take(5)->get();

        // Month-over-Month Sales
        $thisMonthSales = SalesOrder::whereMonth('created_at', Carbon::now()->month)->sum('total_amount');
        $lastMonthSales = SalesOrder::whereMonth('created_at', Carbon::now()->subMonth()->month)->sum('total_amount');
        $salesGrowth = $lastMonthSales > 0 ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        // Current Inventory Status
        $currentTotalStock = Stock::sum('quantity');

        // Stock Intake Comparison (Stock In movements)
        $thisMonthStock = StockMovement::whereIn('type', ['Stock In', 'Initial Stock'])
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('quantity');
            
        $lastMonthStock = StockMovement::whereIn('type', ['Stock In', 'Initial Stock'])
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('quantity');
            
        $stockGrowth = $lastMonthStock > 0 ? (($thisMonthStock - $lastMonthStock) / $lastMonthStock) * 100 : 0;

        return view('backend.dashboard.index', compact(
            'supplierCount', 
            'productCount', 
            'lowStockCount', 
            'todaySales', 
            'unifiedActivities',
            'newSuppliers',
            'latestOrders',
            'thisMonthSales',
            'lastMonthSales',
            'salesGrowth',
            'thisMonthStock',
            'lastMonthStock',
            'stockGrowth',
            'currentTotalStock'
        ));
    }
}
