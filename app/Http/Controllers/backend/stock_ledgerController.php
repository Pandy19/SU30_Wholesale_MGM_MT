<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class stock_ledgerController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'location', 'user']);

        // Filter by Product/SKU/Reference
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%")
                       ->orWhere('sku', 'like', "%{$search}%");
                })->orWhere('reference', 'like', "%{$search}%");
            });
        }

        // Filter by Product ID
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by Type (Action)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by User
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by Date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(15);

        // Summaries
        $totalRecords = StockMovement::count();
        $totalStockIn = StockMovement::whereIn('type', ['Stock In', 'Initial Stock'])->sum('quantity');
        $totalStockOut = abs(StockMovement::where('type', 'Stock Out')->sum('quantity'));

        $products = Product::orderBy('name')->get();
        $users = User::whereIn('role', ['admin', 'staff'])->get();

        return view('backend.stock_ledger.index', compact(
            'movements', 'totalRecords', 'totalStockIn', 'totalStockOut', 'products', 'users'
        ));
    }
}
