<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Customer;

class sales_order_historyController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesOrder::with(['customer']);

        // Filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('type')) {
            $type = $request->type;
            $query->whereHas('customer', function($q) use ($type) {
                $q->where('type', $type);
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date')) {
            $query->whereDate('order_date', $request->date);
        }

        $sales_orders = $query->join('customers', 'sales_orders.customer_id', '=', 'customers.id')
            ->select('sales_orders.*', 'customers.name as customer_name')
            ->orderBy('customers.name', 'asc')
            ->orderBy('sales_orders.id', 'desc')
            ->paginate(30);

        // Summaries
        $totalSales = SalesOrder::count();
        $totalRevenue = SalesOrder::sum('total_amount');
        $pendingPayment = SalesOrder::whereIn('payment_status', ['unpaid', 'partial'])->count();
        $b2bCustomersCount = Customer::where('type', 'B2B')->count();

        $customers = Customer::orderBy('name')->get();

        return view('backend.sales_order_history.index', compact(
            'sales_orders', 
            'totalSales', 
            'totalRevenue', 
            'pendingPayment', 
            'b2bCustomersCount',
            'customers'
        ));
    }
}
