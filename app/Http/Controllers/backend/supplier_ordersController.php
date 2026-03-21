<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class supplier_ordersController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'items']);

        // Filtering
        if ($request->search) {
            $query->where('po_number', 'like', '%' . $request->search . '%');
        }

        if ($request->supplier) {
            $query->where('supplier_id', $request->supplier);
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->date) {
            $query->whereDate('order_date', $request->date);
        }

        $purchase_orders = $query->orderBy('id', 'desc')->get();

        // Summary should ideally be calculated from unfiltered data or based on specific requirements
        // For now, let's show summary based on total database records
        $all_pos = PurchaseOrder::all();
        $summary = [
            'total_orders' => $all_pos->count(),
            'total_amount' => $all_pos->sum('total_amount'),
            'paid_orders' => $all_pos->where('payment_status', 'paid')->count(),
            'unpaid_orders' => $all_pos->where('payment_status', 'unpaid')->count(),
        ];

        $suppliers = Supplier::where('status', 'active')->get();

        return view('backend.supplier_orders.index', compact('purchase_orders', 'summary', 'suppliers'));
    }

    public function manage(Request $request)
    {
        $user = Auth::user();
        $supplier = Supplier::where('email', $user->email ?? '')->first();

        if (!$supplier) {
            // For demo/dev purposes if not logged in as a real supplier
            $supplier = Supplier::first(); 
        }

        $query = PurchaseOrder::where('supplier_id', $supplier->id)->with(['items.product']);

        if ($request->search) {
            $query->where('po_number', 'like', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $purchase_orders = $query->orderBy('id', 'desc')->paginate(30);

        return view('backend.Supplier_Dashboard.manage_orders', compact('purchase_orders', 'supplier'));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $supplier = Supplier::where('email', $user->email ?? '')->first();
        
        if (!$supplier) {
             $supplier = Supplier::first();
        }

        $po = PurchaseOrder::where('id', $id)->where('supplier_id', $supplier->id)->firstOrFail();
        $old_status = $po->status;

        $request->validate([
            'status' => 'required|in:ordered,shipped,delivered,cancelled',
            'remarks' => 'nullable|string',
            'items' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            // Update PO level info
            $po->update([
                'status' => $request->status,
                'remarks' => $request->remarks
            ]);

            // Logic: Deduct Supplier QTY when status moves to 'shipped'
            if ($request->status === 'shipped' && $old_status !== 'shipped') {
                foreach ($po->items as $item) {
                    // Get the quantity the supplier said they are sending from the request
                    $shipped_qty = isset($request->items[$item->id]['shipped_qty']) 
                        ? (int)$request->items[$item->id]['shipped_qty'] 
                        : $item->quantity;

                    // Update the PO item with the confirmed shipped quantity for record keeping
                    // Note: If you don't have a shipped_qty column, we'll just use it for deduction
                    
                    $supplierProduct = \App\Models\SupplierProduct::where('supplier_id', $supplier->id)
                        ->where('product_id', $item->product_id)
                        ->first();
                    
                    if ($supplierProduct) {
                        // Deduct based on confirmed quantity to send
                        $supplierProduct->decrement('available_qty', $shipped_qty);
                    }
                }
            }
            
            // Logic: Revert Supplier QTY if status moves from 'shipped' to 'cancelled'
            if ($request->status === 'cancelled' && $old_status === 'shipped') {
                foreach ($po->items as $item) {
                    // Since we don't store shipped_qty yet, we assume they sent what was ordered
                    // or you could add a column to PO items to store the confirmed shipped_qty
                    $supplierProduct = \App\Models\SupplierProduct::where('supplier_id', $supplier->id)
                        ->where('product_id', $item->product_id)
                        ->first();
                    
                    if ($supplierProduct) {
                        $supplierProduct->increment('available_qty', $item->quantity);
                    }
                }
            }

            // Logic: Create Goods Receiving record when status moves to 'delivered'
            if ($request->status === 'delivered' && $old_status !== 'delivered') {
                // Check if GRN already exists for this PO
                $existingGR = \App\Models\GoodsReceiving::where('purchase_order_id', $po->id)->first();
                
                if (!$existingGR) {
                    $gr = \App\Models\GoodsReceiving::create([
                        'purchase_order_id' => $po->id,
                        'received_date' => now(),
                        'status' => 'pending',
                        'remarks' => 'Auto-generated from supplier delivery: ' . $request->remarks
                    ]);

                    foreach ($po->items as $item) {
                        // Get the quantity the supplier said they are sending (from request or fallback to ordered qty)
                        $shipped_qty = isset($request->items[$item->id]['shipped_qty']) 
                            ? (int)$request->items[$item->id]['shipped_qty'] 
                            : $item->quantity;

                        \App\Models\GoodsReceivingItem::create([
                            'goods_receiving_id' => $gr->id,
                            'product_id' => $item->product_id,
                            'ordered_qty' => $item->quantity,
                            'received_qty' => $shipped_qty,
                            'accepted_qty' => 0,
                            'rejected_qty' => 0,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order status updated and sent to Warehouse for approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }
}
