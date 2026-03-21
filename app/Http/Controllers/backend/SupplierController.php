<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        // Brands for accordion
        $brands = Brand::with(['category', 'suppliers'])
            ->orderBy('name')
            ->paginate(10);

        // Category dropdown
        $categories = Category::orderBy('name')->get();

        // Brand dropdown in supplier modal
        $allBrands = Brand::orderBy('name')->get();

        // Next supplier code (SUP-001...)
        $lastSupplierCode = Supplier::orderBy('id', 'desc')->value('code');
        if ($lastSupplierCode && strlen($lastSupplierCode) >= 7) {
            $num = (int) substr($lastSupplierCode, 4);
            $nextSupplierCode = 'SUP-' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextSupplierCode = 'SUP-001';
        }

        // Next category code (CAT-001...)
        $lastCatCode = Category::orderBy('id', 'desc')->value('code');
        if ($lastCatCode && strlen($lastCatCode) >= 7) {
            $num = (int) substr($lastCatCode, 4);
            $nextCategoryCode = 'CAT-' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextCategoryCode = 'CAT-001';
        }

        return view('backend.suppliers.index', compact(
            'brands',
            'categories',
            'allBrands',
            'nextSupplierCode',
            'nextCategoryCode'
        ));
    }

    public function storeBrand(Request $request)
    {
        $request->validate([
            'brand_name'  => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required|in:active,inactive',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $fileName = Str::slug($request->brand_name) . '_' . now()->format('YmdHis') . '.' . $request->file('logo')->getClientOriginalExtension();
            $logoPath = $request->file('logo')->storeAs('img/brand', $fileName, 'public');
        }

        Brand::create([
            'category_id' => $request->category_id,
            'name'        => $request->brand_name,
            'logo'        => $logoPath,
            'status'      => $request->status,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Brand added successfully');
    }

    public function storeSupplier(Request $request)
    {
        $request->validate([
            'brand_id'        => 'required|exists:brands,id',
            'company_name'    => 'required|string|max:255',
            'payment_term'    => 'required|in:Immediate,Net 7 Days,Net 15 Days,Net 30 Days,Net 60 Days',
            'status'          => 'required|in:active,inactive',
        ]);

        // Generate code
        $lastCode = Supplier::orderBy('id', 'desc')->value('code');
        $num = ($lastCode && strlen($lastCode) >= 7) ? ((int) substr($lastCode, 4) + 1) : 1;
        $supplierCode = 'SUP-' . str_pad($num, 3, '0', STR_PAD_LEFT);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $fileName = $supplierCode . '_' . Str::slug($request->company_name) . '_' . now()->format('YmdHis') . '.' . $request->file('document')->getClientOriginalExtension();
            $documentPath = $request->file('document')->storeAs('img/SupplierLicense', $fileName, 'public');
        }

        Supplier::create([
            'brand_id'        => $request->brand_id,
            'code'            => $supplierCode,
            'company_name'    => $request->company_name,
            'contact_person'  => $request->contact_person,
            'phone'           => $request->phone,
            'email'           => $request->email,
            'address'         => $request->address,
            'payment_term'    => $request->payment_term,
            'lead_time_days'  => $request->lead_time_days,
            'document'        => $documentPath,
            'status'          => $request->status,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier added successfully');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'status'        => 'required|in:active,inactive',
        ]);

        $lastCode = Category::orderBy('id', 'desc')->value('code');
        $num = ($lastCode && strlen($lastCode) >= 7) ? ((int) substr($lastCode, 4) + 1) : 1;
        $categoryCode = 'CAT-' . str_pad($num, 3, '0', STR_PAD_LEFT);

        Category::create([
            'code' => $categoryCode,
            'name' => $request->category_name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Category added successfully');
    }

    public function updateSupplier(Request $request, Supplier $supplier)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'payment_term' => 'required|in:Immediate,Net 7 Days,Net 15 Days,Net 30 Days,Net 60 Days',
            'status'       => 'required|in:active,inactive',
        ]);

        $documentPath = $supplier->document;
        if ($request->hasFile('document')) {
            $fileName = $supplier->code . '_' . Str::slug($request->company_name) . '_' . now()->format('YmdHis') . '.' . $request->file('document')->getClientOriginalExtension();
            $documentPath = $request->file('document')->storeAs('img/SupplierLicense', $fileName, 'public');
        }

        $supplier->update([
            'company_name'   => $request->company_name,
            'contact_person' => $request->contact_person,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'address'        => $request->address,
            'payment_term'   => $request->payment_term,
            'lead_time_days' => $request->lead_time_days,
            'document'       => $documentPath,
            'status'         => $request->status,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully');
    }

    public function deleteSupplier(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully');
    }
}
