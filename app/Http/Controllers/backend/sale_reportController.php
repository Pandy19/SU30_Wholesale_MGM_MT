<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class sale_reportController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesOrder::with(['customer', 'items.product']);

        // Applying Filters
        if ($request->filled('start_date')) {
            $query->whereDate('order_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('order_date', '<=', $request->end_date);
        }
        if ($request->filled('customer_type')) {
            $type = $request->customer_type;
            $query->whereHas('customer', function($q) use ($type) {
                $q->where('type', $type);
            });
        }

        // Summary Calculations
        $totalSales = (clone $query)->sum('total_amount');
        $totalOrders = (clone $query)->count();
        
        $b2bRevenue = (clone $query)->whereHas('customer', function($q) {
            $q->where('type', 'B2B');
        })->sum('total_amount');
        
        $b2cRevenue = (clone $query)->whereHas('customer', function($q) {
            $q->where('type', 'B2C');
        })->sum('total_amount');

        // Paginated details
        $sales_details = (clone $query)->orderBy('id', 'desc')->paginate(15);

        // --- PROFITABILITY DATA ---
        // Calculate Cost for B2B vs B2C
        $b2bOrders = (clone $query)->whereHas('customer', function($q) { $q->where('type', 'B2B'); })->with('items.product')->get();
        $b2cOrders = (clone $query)->whereHas('customer', function($q) { $q->where('type', 'B2C'); })->with('items.product')->get();
        
        $b2bCost = 0;
        foreach($b2bOrders as $o) {
            foreach($o->items as $i) {
                $stock = DB::table('stocks')->where('product_id', $i->product_id)->first();
                $b2bCost += ($stock && $stock->average_cost > 0) ? $stock->average_cost * $i->quantity : ($i->unit_price * 0.7) * $i->quantity;
            }
        }

        $b2cCost = 0;
        foreach($b2cOrders as $o) {
            foreach($o->items as $i) {
                $stock = DB::table('stocks')->where('product_id', $i->product_id)->first();
                $b2cCost += ($stock && $stock->average_cost > 0) ? $stock->average_cost * $i->quantity : ($i->unit_price * 0.7) * $i->quantity;
            }
        }

        // --- TREND DATA ---
        $allFilteredOrders = (clone $query)->with('items.product')->get();
        $monthlyData = [];
        $totalCost = 0;
        foreach($allFilteredOrders as $order) {
            $month = date('M Y', strtotime($order->order_date));
            $sortKey = date('Y-m', strtotime($order->order_date));
            if (!isset($monthlyData[$sortKey])) { $monthlyData[$sortKey] = ['month' => $month, 'revenue' => 0, 'cost' => 0]; }
            $monthlyData[$sortKey]['revenue'] += (float)$order->total_amount;
            $orderCost = 0;
            foreach($order->items as $item) {
                $stockRecord = DB::table('stocks')->where('product_id', $item->product_id)->first();
                $orderCost += ($stockRecord && $stockRecord->average_cost > 0) ? $stockRecord->average_cost * $item->quantity : ($item->unit_price * 0.7) * $item->quantity;
            }
            $monthlyData[$sortKey]['cost'] += $orderCost;
            $totalCost += $orderCost;
        }
        ksort($monthlyData);
        $monthlyTrend = collect(array_values($monthlyData));

        return view('backend.sale_report.index', compact(
            'sales_details', 'totalSales', 'totalOrders', 'b2bRevenue', 'b2cRevenue',
            'monthlyTrend', 'totalCost', 'b2bCost', 'b2cCost'
        ));
    }
}
