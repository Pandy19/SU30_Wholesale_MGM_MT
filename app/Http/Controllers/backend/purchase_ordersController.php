<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class purchase_ordersController extends Controller
{
    public function index()
    {
        $cart = session()->get('purchase_cart', []);
        
        // Always fetch all purchase orders from database for the list view
        $purchase_orders = PurchaseOrder::with(['supplier', 'items.product'])->orderBy('id', 'desc')->get();

        if (empty($cart)) {
            // If cart is empty, we show the list of POs from database
            return view('backend.purchase_orders.index', compact('purchase_orders', 'cart'));
        }

        // Group by supplier for the draft/checkout page
        $grouped_cart = [];
        foreach ($cart as $key => $item) {
            // Fix image URL if it's just a path
            if (isset($item['image']) && !empty($item['image']) && !filter_var($item['image'], FILTER_VALIDATE_URL)) {
                $item['image'] = \Illuminate\Support\Facades\Storage::disk('public')->url($item['image']);
            } elseif (!isset($item['image']) || empty($item['image'])) {
                $item['image'] = asset('assets/dist/img/default-150x150.png');
            }

            $supplier_id = $item['supplier_id'];
            if (!isset($grouped_cart[$supplier_id])) {
                $supplier = Supplier::find($supplier_id);
                $grouped_cart[$supplier_id] = [
                    'supplier' => $supplier,
                    'items' => []
                ];
            }
            $grouped_cart[$supplier_id]['items'][] = $item;
        }

        return view('backend.purchase_orders.index', compact('grouped_cart', 'cart', 'purchase_orders'));
    }

    public function store(Request $request)
    {
        $cart = Session::get('purchase_cart', []);

        if (empty($cart)) {
            return response()->json(['message' => 'Your cart is empty'], 400);
        }

        // Group by supplier
        $grouped_cart = [];
        foreach ($cart as $item) {
            $grouped_cart[$item['supplier_id']][] = $item;
        }

        try {
            DB::beginTransaction();

            // Convert due date from DD/MM/YYYY to YYYY-MM-DD
            $due_date = null;
            if ($request->due_date) {
                try {
                    $due_date = Carbon::createFromFormat('d/m/Y', $request->due_date)->format('Y-m-d');
                } catch (\Exception $e) {
                    $due_date = null;
                }
            }

            $newly_created_ids = [];

            foreach ($grouped_cart as $supplier_id => $items) {
                // Generate PO Number (e.g., PO-20260320-001)
                $date = date('Ymd');
                $count = PurchaseOrder::whereDate('created_at', today())->count() + 1;
                $po_number = 'PO-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

                // Create Purchase Order
                $total_amount = array_reduce($items, function($carry, $item) {
                    return $carry + ($item['price'] * $item['qty']);
                }, 0);

                $purchase_order = PurchaseOrder::create([
                    'po_number' => $po_number,
                    'supplier_id' => $supplier_id,
                    'order_date' => today(),
                    'total_amount' => $total_amount,
                    'status' => 'pending',
                    'payment_status' => strtolower($request->payment_status ?? 'unpaid'),
                    'payment_method' => $request->payment_method,
                    'payment_term' => $request->payment_term,
                    'due_date' => $due_date,
                    'remarks' => $request->remarks
                ]);

                $newly_created_ids[] = $purchase_order->id;

                // Create Purchase Order Items
                foreach ($items as $item) {
                    PurchaseOrderItem::create([
                        'purchase_order_id' => $purchase_order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['qty'],
                        'unit_cost' => $item['price'],
                        'line_total' => $item['price'] * $item['qty'],
                        'received_qty' => 0
                    ]);
                }
            }

            DB::commit();

            // Store IDs in session for the confirmation page
            Session::put('last_po_ids', $newly_created_ids);
            
            // Clear the cart session after successful storage
            Session::forget('purchase_cart');
            
            return response()->json([
                'message' => 'Purchase Orders created successfully',
                'redirect' => route('purchase_orders.confirm_payment')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating Purchase Orders: ' . $e->getMessage()], 500);
        }
    }

    public function confirmPayment(Request $request)
    {
        // Check if we are viewing a specific past PO via query param
        $session_ids = $request->query('session_ids');
        if ($session_ids) {
            $id_array = explode(',', $session_ids);
        } else {
            // Otherwise show the last ones created in this session
            $id_array = Session::get('last_po_ids', []);
        }
        
        if (empty($id_array)) {
            // Revert back to index or a more general confirmed view if no IDs in session
            return redirect()->route('purchase_orders.index');
        }

        $purchase_orders = PurchaseOrder::with(['supplier', 'items.product'])
                            ->whereIn('id', $id_array)
                            ->get();

        return view('backend.purchase_orders.confirm_payment', compact('purchase_orders'));
    }
}
