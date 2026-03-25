<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use DB;

class AdminSupplierContentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Supplier::withCount(['supplierProducts' => function($query) {
                $query->whereHas('product', function($q) {
                    $q->where('status', '!=', 'inactive');
                });
            }])
            ->whereHas('supplierProducts', function($query) {
                $query->whereHas('product', function($q) {
                    $q->where('status', '!=', 'inactive');
                });
            });

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('company_name', 'LIKE', "%{$search}%");
            });
        }

        $suppliers = $query->paginate($perPage);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('backend.admin_supplier_content.partials.supplier_table', compact('suppliers'))->render(),
                'pagination' => view('backend.admin_supplier_content.partials.pagination', compact('suppliers'))->render(),
                'total' => $suppliers->total(),
                'firstItem' => $suppliers->firstItem() ?? 0,
                'lastItem' => $suppliers->lastItem() ?? 0,
            ]);
        }

        return view('backend.admin_supplier_content.index', compact('suppliers', 'search'));
    }

    public function getProducts($id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $offers = SupplierProduct::where('supplier_id', $id)
            ->whereHas('product', function($q) {
                $q->where('status', '!=', 'inactive');
            })
            ->with(['product.brand', 'product.category'])
            ->get()
            ->map(function($offer) {
                return [
                    'id' => $offer->id,
                    'product_name' => $offer->product->name,
                    'price' => number_format($offer->price, 2),
                    'qty' => $offer->available_qty,
                    'status' => $offer->product->status,
                    'image' => $offer->product->image ? asset('storage/' . $offer->product->image) : asset('assets/dist/img/default-150x150.png'),
                    'brand' => $offer->product->brand->name ?? 'N/A',
                    'category' => $offer->product->category->name ?? 'N/A',
                    'edit_url' => route('admin.supplier_content.edit', $offer->id),
                    'toggle_url' => route('admin.supplier_content.toggle_visibility', $offer->id),
                    'delete_url' => route('admin.supplier_content.destroy', $offer->id),
                ];
            });

        return response()->json([
            'supplier_name' => $supplier->company_name,
            'offers' => $offers
        ]);
    }

    public function edit($id)
    {
        $offer = SupplierProduct::with(['product', 'supplier'])->findOrFail($id);
        
        if ($offer->product->status === 'inactive') {
            return redirect()->route('admin.supplier_content.index')->with('error', 'This product is inactive.');
        }

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('backend.admin_supplier_content.edit', compact('offer', 'brands', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $offer = SupplierProduct::findOrFail($id);
        $product = Product::findOrFail($offer->product_id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'brand_id'      => 'required|exists:brands,id',
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|numeric|min:0',
            'available_qty' => 'required|integer|min:0',
            'status'        => 'required|in:available,limited,unavailable,hidden,inactive',
            'description'   => 'nullable|string',
            'specs'         => 'nullable|string',
            'image'         => 'nullable|image|max:2048',
        ]);

        $productData = [
            'name'        => $request->name,
            'brand_id'    => $request->brand_id,
            'category_id' => $request->category_id,
            'specs'       => $request->specs,
            'description' => $request->description,
            'status'      => $request->status,
        ];

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $productData['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($productData);

        $offer->update([
            'price'         => $request->price,
            'available_qty' => $request->available_qty,
        ]);

        return redirect()->route('admin.supplier_content.index')->with('success', 'Supplier post updated.');
    }

    public function destroy($id)
    {
        $offer = SupplierProduct::findOrFail($id);
        $product = Product::findOrFail($offer->product_id);
        $product->status = 'inactive';
        $product->save();

        return back()->with('success', 'Supplier post marked as inactive.');
    }

    public function toggleVisibility($id)
    {
        $offer = SupplierProduct::findOrFail($id);
        $product = Product::findOrFail($offer->product_id);

        $product->status = ($product->status === 'hidden') ? 'available' : 'hidden';
        $product->save();

        return back()->with('success', 'Post visibility updated.');
    }
}
