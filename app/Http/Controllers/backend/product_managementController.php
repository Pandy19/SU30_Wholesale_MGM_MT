<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class product_managementController extends Controller
{
    public function index()
{
    $products = DB::table('inventory_products as p')
        ->leftJoin('procurement_purchase_order_items as poi', 'poi.product_id', '=', 'p.product_id')
        ->leftJoin('procurement_suppliers as s', 's.supplier_id', '=', 'poi.supplier_id')
        ->select(
            'p.product_id',
            'p.sku',
            DB::raw('p.name as product_name'),
            'p.image_url',
            'p.specs_text',
            'p.status',

            // TEMP values (because you don't have brand/category tables)
            DB::raw("MAX(s.company_name) as brand_name"),
            DB::raw("'-' as category_name"),

            DB::raw('COUNT(DISTINCT poi.supplier_id) as supplier_count'),
            DB::raw('MIN(poi.unit_cost) as best_price')
        )
        ->groupBy(
            'p.product_id',
            'p.sku',
            'p.name',
            'p.image_url',
            'p.specs_text',
            'p.status'
        )
        ->orderBy('p.product_id', 'desc')
        ->get();

    return view('backend.product_management.index', compact('products'));
}

    public function details($product_id)
    {
        // 1) Product header + specs
        $product = DB::table('inventory_products as p')
            ->select(
                'p.product_id',
                'p.sku',
                DB::raw('p.name as product_name'),
                'p.image_url',
                'p.specs_text'
            )
            ->where('p.product_id', $product_id)
            ->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // 2) Available suppliers (latest row per supplier)
        $suppliersRaw = DB::table('procurement_purchase_order_items as poi')
            ->join('procurement_suppliers as s', 's.supplier_id', '=', 'poi.supplier_id')
            ->select(
                's.supplier_id',
                's.company_name',
                's.supplier_code',
                's.phone',
                's.email',
                's.lead_time_days',
                's.status as supplier_status',
                'poi.unit_cost',
                'poi.quantity',
                'poi.created_at'
            )
            ->where('poi.product_id', $product_id)
            ->orderBy('poi.created_at', 'desc')
            ->get();

        $suppliers = $suppliersRaw
            ->unique('supplier_id')
            ->values()
            ->map(function ($row) {
                return [
                    'supplier_id'   => $row->supplier_id,
                    'company_name'  => $row->company_name,
                    'supplier_code' => $row->supplier_code,
                    'phone'         => $row->phone,
                    'email'         => $row->email,
                    'cost_price'    => (float)$row->unit_cost,
                    'available_qty' => (int)$row->quantity,
                    'lead_time'     => $row->lead_time_days ? ($row->lead_time_days . ' Days') : '—',
                    'status'        => $row->supplier_status ?? 'Active',
                ];
            });

        // 3) Price history
        $price_history = DB::table('procurement_purchase_order_items as poi')
            ->join('procurement_suppliers as s', 's.supplier_id', '=', 'poi.supplier_id')
            ->select(
                DB::raw('s.company_name as supplier_name'),
                DB::raw('poi.unit_cost as purchase_price'),
                DB::raw('DATE(poi.created_at) as purchase_date'),
                DB::raw('DATE(poi.updated_at) as last_updated')
            )
            ->where('poi.product_id', $product_id)
            ->orderBy('poi.created_at', 'desc')
            ->limit(30)
            ->get();

        // If you don't have brand table, just show dash or something you want
        $brandName = '—';

        return response()->json([
            'product' => [
                'product_id'   => $product->product_id,
                'sku'          => $product->sku,
                'product_name' => $product->product_name,
                'image_url'    => $product->image_url,
                'specs_text'   => $product->specs_text,
                'brand_name'   => $brandName,
            ],
            'suppliers' => $suppliers,
            'price_history' => $price_history,
        ]);
    }
}