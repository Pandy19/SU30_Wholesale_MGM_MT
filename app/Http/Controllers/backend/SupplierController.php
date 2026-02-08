<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

use App\Models\ProcurementBrand;
use App\Models\ProcurementCategory;
use App\Models\ProcurementSupplier;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        // Brands for accordion
        $brands = ProcurementBrand::with(['category', 'suppliers.category'])
            ->orderBy('brand_name')
            ->paginate(10);

        // Category dropdown
        $categories = ProcurementCategory::orderBy('category_name')->get();

        // Brand dropdown in supplier modal (not paginated)
        $allBrands = ProcurementBrand::orderBy('brand_name')->get();

        // Next supplier code (SUP-001...)
        $lastSupplierCode = ProcurementSupplier::orderBy('supplier_id', 'desc')->value('supplier_code');
        if ($lastSupplierCode && strlen($lastSupplierCode) >= 7) {
            $num = (int) substr($lastSupplierCode, 4);
            $nextSupplierCode = 'SUP-' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextSupplierCode = 'SUP-001';
        }

        // Next category code (CAT-001...)
        $lastCatCode = ProcurementCategory::orderBy('category_id', 'desc')->value('category_code');
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

    /**
     * POST: Add Brand
     * Upload logo -> storage/app/public/img/brand
     * URL -> public/storage/img/brand
     */
    public function storeBrand(Request $request)
    {
        $request->validate([
            'brand_name'  => 'required|string|max:255',
            'category_id' => 'required|exists:procurement_categories,category_id',
            'status'      => 'required|in:active,inactive',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $slug = Str::slug($request->brand_name);
            $date = Carbon::now()->format('YmdHis');
            $ext  = $request->file('logo')->getClientOriginalExtension();

            $fileName = $slug . '_' . $date . '.' . $ext;

            // Save to storage/app/public/img/brand
            $logoPath = $request->file('logo')->storeAs('img/brand', $fileName, 'public');
        }

        ProcurementBrand::create([
            'category_id' => $request->category_id,
            'brand_name'  => $request->brand_name,
            'brand_slug'  => Str::slug($request->brand_name),
            'logo_path'   => $logoPath,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Brand added successfully');
    }

    /**
     * POST: Add Supplier
     * Auto supplier_code: SUP-001...
     * Upload license -> storage/app/public/img/SupplierLicense
     */
    public function storeSupplier(Request $request)
    {
        $request->validate([
            'category_id'     => 'required|exists:procurement_categories,category_id',
            'brand_id'        => 'required|exists:procurement_brands,brand_id',

            'company_name'    => 'required|string|max:255',
            'contact_person'  => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string|max:255',

            // ✅ MUST match your ENUM exactly
            'payment_term'    => 'required|in:Immediate,Net 7 Days,Net 15 Days,Net 30 Days,Net 60 Days',

            'lead_time_days'  => 'nullable|integer|min:0|max:3650',
            'status'          => 'required|in:active,inactive',

            // 5MB, allow image/pdf/doc/docx
            'document'        => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf,doc,docx|max:5120',
        ]);

        // Generate supplier_code on backend
        $lastSupplierCode = ProcurementSupplier::orderBy('supplier_id', 'desc')->value('supplier_code');
        $num = ($lastSupplierCode && strlen($lastSupplierCode) >= 7)
            ? ((int) substr($lastSupplierCode, 4) + 1)
            : 1;

        $supplierCode = 'SUP-' . str_pad($num, 3, '0', STR_PAD_LEFT);

        // Upload document
        $documentPath = null;
        if ($request->hasFile('document')) {
            $companySlug = Str::slug($request->company_name);
            $date = Carbon::now()->format('YmdHis');
            $ext  = $request->file('document')->getClientOriginalExtension();

            $fileName = $supplierCode . '_' . $companySlug . '_' . $date . '.' . $ext;

            // Save to storage/app/public/img/SupplierLicense
            $documentPath = $request->file('document')->storeAs('img/SupplierLicense', $fileName, 'public');
        }

        ProcurementSupplier::create([
            'brand_id'        => $request->brand_id,
            'category_id'     => $request->category_id,
            'supplier_code'   => $supplierCode,

            'company_name'    => $request->company_name,
            'contact_person'  => $request->contact_person,
            'phone'           => $request->phone,
            'email'           => $request->email,
            'address'         => $request->address,

            'payment_term'    => $request->payment_term,
            'lead_time_days'  => $request->lead_time_days,

            'document_path'   => $documentPath,
            'status'          => $request->status,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier added successfully');
    }

    /**
     * POST: Add Category
     * Auto category_code: CAT-001...
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description'   => 'nullable|string',
            'status'        => 'required|in:active,inactive',
        ]);

        // Generate category_code on backend
        $lastCatCode = ProcurementCategory::orderBy('category_id', 'desc')->value('category_code');
        $num = ($lastCatCode && strlen($lastCatCode) >= 7)
            ? ((int) substr($lastCatCode, 4) + 1)
            : 1;

        $categoryCode = 'CAT-' . str_pad($num, 3, '0', STR_PAD_LEFT);

        ProcurementCategory::create([
            'category_code' => $categoryCode,
            'category_name' => $request->category_name,
            'description'   => $request->description,
            'status'        => $request->status,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Category added successfully');
    }

    public function updateSupplier(Request $request, ProcurementSupplier $supplier)
    {
        $request->validate([
            'company_name'    => 'required|string|max:255',
            'contact_person'  => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string|max:255',

            // MUST match your ENUM exactly
            'payment_term'    => 'required|in:Immediate,Net 7 Days,Net 15 Days,Net 30 Days,Net 60 Days',

            'lead_time_days'  => 'nullable|integer|min:0|max:3650',
            'status'          => 'required|in:active,inactive',

            // optional: update license
            'document'        => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf,doc,docx|max:5120',
        ]);

        // Upload new license (optional)
        $documentPath = $supplier->document_path;

        if ($request->hasFile('document')) {
            $companySlug = Str::slug($request->company_name);
            $date = Carbon::now()->format('YmdHis');
            $ext  = $request->file('document')->getClientOriginalExtension();

            $fileName = $supplier->supplier_code . '_' . $companySlug . '_' . $date . '.' . $ext;

            $documentPath = $request->file('document')
                ->storeAs('img/SupplierLicense', $fileName, 'public');
        }

        $supplier->update([
            'company_name'   => $request->company_name,
            'contact_person' => $request->contact_person,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'address'        => $request->address,
            'payment_term'   => $request->payment_term,
            'lead_time_days' => $request->lead_time_days,
            'document_path'  => $documentPath,
            'status'         => $request->status,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier updated successfully');
    }

    public function deleteSupplier(ProcurementSupplier $supplier)
    {
        try {
            $supplier->delete();

            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Supplier deleted successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            // Most common: foreign key constraint (supplier used in PO / goods receiving / etc.)
            return redirect()
                ->route('suppliers.index')
                ->with('error', 'Cannot delete this supplier because it is already used in other records (Purchase Orders / Goods Receiving / etc.).');
        }
    }


}
