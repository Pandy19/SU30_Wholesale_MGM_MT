<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Customer;
use Carbon\Carbon;

class customer_paymentsController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesOrder::with(['customer']);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('type')) {
            $type = $request->type;
            $query->whereHas('customer', function($q) use ($type) {
                $q->where('type', $type);
            });
        }

        if ($request->filled('payment_status')) {
            $status = $request->payment_status;
            if ($status == 'overdue') {
                $query->where('payment_status', '!=', 'paid')
                      ->where('due_date', '<', now());
            } else {
                $query->where('payment_status', $status);
            }
        }

        if ($request->filled('date')) {
            $query->whereDate('order_date', $request->date);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $sales_orders = $query->orderBy('id', 'desc')->paginate(15);

        // Summaries
        $totalInvoices = SalesOrder::count();
        $paidInvoices = SalesOrder::where('payment_status', 'paid')->count();
        $pendingPayments = SalesOrder::whereIn('payment_status', ['unpaid', 'partial'])->count();
        
        // Calculate Overdue Amount (Simplified: total amount of all non-paid orders past due date)
        $overdueAmount = SalesOrder::where('payment_status', '!=', 'paid')
                                   ->where('due_date', '<', now())
                                   ->sum('total_amount'); // In real app, subtract partial payments

        $customers = Customer::orderBy('name')->get();

        return view('backend.customer_payments.index', compact(
            'sales_orders', 
            'totalInvoices', 
            'paidInvoices', 
            'pendingPayments', 
            'overdueAmount',
            'customers'
        ));
    }

    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required',
            'payment_date' => 'required|date',
            'payment_note' => 'nullable|string',
        ]);

        $order = SalesOrder::findOrFail($id);
        
        $newPaidAmount = $order->paid_amount + $request->payment_amount;
        
        // Determine Status
        $status = 'partial';
        if ($newPaidAmount >= $order->total_amount) {
            $status = 'paid';
            $newPaidAmount = $order->total_amount; // Cap it
        } elseif ($newPaidAmount <= 0) {
            $status = 'unpaid';
        }

        $order->update([
            'paid_amount' => $newPaidAmount,
            'payment_status' => $status,
            'payment_method' => $request->payment_method, // Update to latest method
            'payment_note' => $request->payment_note,
        ]);

        return redirect()->back()->with('success', 'Payment of $' . number_format($request->payment_amount, 2) . ' received for Order #' . $order->order_number);
    }
}
