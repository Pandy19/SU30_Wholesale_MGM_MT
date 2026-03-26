<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GoodsReceiving;
use App\Models\GoodsReceivingItem;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class goods_receivingController extends Controller
{
    public function exportExcel(Request $request)
    {
        $query = GoodsReceivingItem::with([
            'goodsReceiving.purchaseOrder.supplier',
            'goodsReceiving.approver',
            'product.brand',
            'product.category'
        ]);

        // Filters (Consistent with index method)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereHas('goodsReceiving', function ($q) use ($request) {
                $q->whereBetween('received_date', [$request->start_date, $request->end_date]);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })->orWhereHas('goodsReceiving.purchaseOrder', function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('brand_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('brand_id', $request->brand_id);
            });
        }

        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('supplier_id')) {
            $query->whereHas('goodsReceiving.purchaseOrder', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('goodsReceiving', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $items = $query->orderBy('id', 'desc')->get();

        $response = new StreamedResponse(function () use ($items) {
            $handle = fopen('php://output', 'w');
            
            // BOM for Excel to recognize UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Table headers
            fputcsv($handle, [
                'Product Name',
                'SKU',
                'Brand',
                'Supplier',
                'Ordered Qty',
                'Received Qty',
                'Accepted Qty',
                'Rejected Qty',
                'Status',
                'Approved By',
                'Received Date'
            ]);

            // Data rows
            foreach ($items as $item) {
                $gr = $item->goodsReceiving;
                
                // Determine Status Label
                $itemProcessed = ($item->accepted_qty > 0 || $item->rejected_qty > 0);
                if (!$itemProcessed) {
                    $statusLabel = 'Pending';
                } else {
                    if ($item->rejected_qty > 0 && $item->accepted_qty == 0) {
                        $statusLabel = 'Rejected';
                    } elseif ($item->accepted_qty > 0 && $item->rejected_qty == 0) {
                        $statusLabel = 'Approved';
                    } else {
                        $statusLabel = 'Partially Approved';
                    }
                }

                fputcsv($handle, [
                    $item->product->name ?? 'N/A',
                    $item->product->sku ?? 'N/A',
                    $item->product->brand->name ?? 'N/A',
                    $gr->purchaseOrder->supplier->company_name ?? 'N/A',
                    $item->ordered_qty,
                    $item->received_qty,
                    $item->accepted_qty,
                    $item->rejected_qty,
                    $statusLabel,
                    $gr->approver->name ?? '—',
                    $gr->received_date ?? '—',
                ]);
            }

            fclose($handle);
        });

        $filename = 'goods_receiving_report_' . date('Ymd_His') . '.csv';

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    public function index(Request $request)
    {
        $brands = Brand::where('status', 'active')->get();
        $categories = Category::where('status', 'active')->get();
        $suppliers = Supplier::where('status', 'active')->get();

        $query = GoodsReceivingItem::with([
            'goodsReceiving.purchaseOrder.supplier',
            'goodsReceiving.approver',
            'product.brand',
            'product.category'
        ]);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })->orWhereHas('goodsReceiving.purchaseOrder', function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('brand_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('brand_id', $request->brand_id);
            });
        }

        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('supplier_id')) {
            $query->whereHas('goodsReceiving.purchaseOrder', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('goodsReceiving', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->orderBy('id', 'desc')->paginate($perPage);
        $stockLocationCount = \App\Models\StockLocation::count();

        // Fetch unit cost for each item from its PurchaseOrder
        foreach ($items as $item) {
            $unit_cost = 0;
            if ($item->goodsReceiving && $item->goodsReceiving->purchase_order_id) {
                $poItem = PurchaseOrderItem::where('purchase_order_id', $item->goodsReceiving->purchase_order_id)
                                            ->where('product_id', $item->product_id)
                                            ->first();
                $unit_cost = $poItem ? $poItem->unit_cost : 0;
            }
            $item->unit_cost = $unit_cost;
            $item->total_cost = $item->ordered_qty * $unit_cost;
        }

        return view('backend.goods_receiving.index', compact('items', 'brands', 'categories', 'suppliers', 'stockLocationCount'));
    }

    public function approve(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $gr = GoodsReceiving::findOrFail($id);
            
            // If there's any rejection remarks, we store them
            if ($request->filled('remarks')) {
                // If there are existing remarks, append them
                $newRemarks = $request->remarks;
                if ($gr->remarks && strpos($gr->remarks, $newRemarks) === false) {
                    $gr->remarks .= " | " . $newRemarks;
                } else {
                    $gr->remarks = $newRemarks;
                }
                $gr->save();
            }

            // Update items
            foreach ($gr->items as $item) {
                // Check if this specific item is being processed in this request
                if ($request->has("accepted_qty_{$item->id}")) {
                    $accepted_qty = $request->input("accepted_qty_{$item->id}", 0);
                    $rejected_qty = $request->input("rejected_qty_{$item->id}", 0);

                    $item->update([
                        'accepted_qty' => $accepted_qty,
                        'rejected_qty' => $rejected_qty,
                    ]);
                }
            }

            // Check if ALL items in this GoodsReceiving are now processed
            $allProcessed = true;
            foreach ($gr->items()->get() as $item) {
                if ($item->accepted_qty == 0 && $item->rejected_qty == 0 && $item->received_qty > 0) {
                    $allProcessed = false;
                    break;
                }
            }

            if ($allProcessed) {
                $gr->update([
                    'status' => 'accepted',
                    'approved_by' => auth()->id() ?? 1,
                    'received_date' => now(),
                ]);

                // Update PurchaseOrder status to 'received' instead of 'completed' 
                // as it still needs to be put into stock later.
                if ($gr->purchaseOrder) {
                    $gr->purchaseOrder->update(['status' => 'received']);
                }
                
                $message = 'Goods successfully inspected and marked as approved.';
            } else {
                $message = 'Item inspected successfully. Some items in this shipment are still pending.';
            }

            DB::commit();
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $gr = GoodsReceiving::findOrFail($id);
            $gr->update([
                'status' => 'rejected',
                'approved_by' => auth()->id() ?? 1,
                'remarks' => $request->remarks,
                'received_date' => now(),
            ]);

            foreach ($gr->items as $item) {
                $item->update([
                    'accepted_qty' => 0,
                    'rejected_qty' => $item->received_qty,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Goods Receiving rejected.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function storeLocation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:stock_locations,code',
        ]);

        try {
            $location = \App\Models\StockLocation::create([
                'name' => $request->name,
                'code' => $request->code,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Stock location created successfully.',
                'location' => $location
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
