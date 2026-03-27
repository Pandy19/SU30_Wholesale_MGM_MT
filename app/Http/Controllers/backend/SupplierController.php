<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\SupplierStatusMail;
use Illuminate\Support\Facades\Hash;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        // Brands for accordion
        $brands = Brand::with(['category', 'suppliers.user'])
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
            'brand_name'  => 'required|string|max:255|unique:brands,name',
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

    public function updateBrand(Request $request, Brand $brand)
    {
        $request->validate([
            'brand_name'  => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required|in:active,inactive',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $logoPath = $brand->logo;
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($brand->logo && \Storage::disk('public')->exists($brand->logo)) {
                \Storage::disk('public')->delete($brand->logo);
            }
            
            $fileName = Str::slug($request->brand_name) . '_' . now()->format('YmdHis') . '.' . $request->file('logo')->getClientOriginalExtension();
            $logoPath = $request->file('logo')->storeAs('img/brand', $fileName, 'public');
        }

        $brand->update([
            'category_id' => $request->category_id,
            'name'        => $request->brand_name,
            'logo'        => $logoPath,
            'status'      => $request->status,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Brand updated successfully');
    }

    public function storeSupplier(Request $request)
    {
        $request->validate([
            'brand_id'        => 'required|exists:brands,id',
            'company_name'    => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:8|confirmed',
            'payment_term'    => 'required|in:Immediate,Net 7 Days,Net 15 Days,Net 30 Days,Net 60 Days',
            'status'          => 'required|in:active,inactive',
            'document'        => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf,doc,docx|max:5120',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // 1. Create User Account
        $supplierRole = Role::where('slug', 'supplier')->first();
        if (!$supplierRole) {
            return redirect()->back()->with('error', 'Supplier role not found in the system.');
        }

        // Handle profile picture
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $contactPerson = $request->contact_person ?: 'Supplier';
            $extension = $request->file('profile_picture')->getClientOriginalExtension();
            // Format: Contact Person (Full Name) then date format
            $fileName = $contactPerson . '_' . now()->format('Ymd_His') . '.' . $extension;
            $profilePicturePath = $request->file('profile_picture')->storeAs('supplier_profile', $fileName, 'public');
        }

        $user = User::create([
            'name'            => $request->company_name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'status'          => $request->status, // active or inactive
            'role'            => 'supplier',
            'role_id'         => $supplierRole->id,
            'profile_picture' => $profilePicturePath,
            'user_code'       => $this->generateUserCode('supplier'),
        ]);

        // 2. Generate Supplier Code
        $lastCode = Supplier::orderBy('id', 'desc')->value('code');
        $num = ($lastCode && strlen($lastCode) >= 7) ? ((int) substr($lastCode, 4) + 1) : 1;
        $supplierCode = 'SUP-' . str_pad($num, 3, '0', STR_PAD_LEFT);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $extension = $request->file('document')->getClientOriginalExtension();
            $fileName = $supplierCode . '-' . $request->company_name . '.' . $extension;
            $documentPath = $request->file('document')->storeAs('img/SupplierLicense', $fileName, 'public');
        }

        // 3. Create Supplier Profile
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

        return redirect()->route('suppliers.index')->with('success', 'Supplier and User Account created successfully');
    }

    private function generateUserCode($roleSlug)
    {
        // Generate User Code: RoleName-001-MMO
        $roleName = ucfirst($roleSlug);
        $prefix = $roleName . '-';
        
        // Find the highest existing number for this prefix across ALL users
        $lastUserWithPrefix = User::where('user_code', 'LIKE', "{$prefix}%")
            ->orderBy('user_code', 'desc')
            ->first();
        
        $nextNumber = 1;
        if ($lastUserWithPrefix && $lastUserWithPrefix->user_code) {
            // Extract the number from prefix-XXX-MMO
            $parts = explode('-', $lastUserWithPrefix->user_code);
            if (count($parts) >= 2 && is_numeric($parts[1])) {
                $nextNumber = intval($parts[1]) + 1;
            }
        }
        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT) . '-MMO';
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,name',
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
            'company_name'    => 'required|string|max:255',
            'payment_term'    => 'required|in:Immediate,Net 7 Days,Net 15 Days,Net 30 Days,Net 60 Days',
            'status'          => 'required|in:active,inactive',
            'document'        => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf,doc,docx|max:5120',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Find associated user
        $user = User::where('email', $supplier->email)->first();

        // Handle profile picture update
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture
            if ($user && $user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
                \Storage::disk('public')->delete($user->profile_picture);
            }

            $contactPerson = $request->contact_person ?: 'Supplier';
            $extension = $request->file('profile_picture')->getClientOriginalExtension();
            $fileName = $contactPerson . '_' . now()->format('Ymd_His') . '.' . $extension;
            $profilePicturePath = $request->file('profile_picture')->storeAs('supplier_profile', $fileName, 'public');

            if ($user) {
                $user->update(['profile_picture' => $profilePicturePath]);
            }
        }

        // Update User basic info if email or name changed
        if ($user) {
            $user->update([
                'name'   => $request->company_name,
                'status' => $request->status,
                // Email update is tricky if it's unique, but assuming it stays same for now or handled via unique validation
            ]);
        }

        $documentPath = $supplier->document;
        if ($request->hasFile('document')) {
            // Delete old document
            if ($supplier->document && \Storage::disk('public')->exists($supplier->document)) {
                \Storage::disk('public')->delete($supplier->document);
            }

            $extension = $request->file('document')->getClientOriginalExtension();
            $fileName = $supplier->code . '-' . $request->company_name . '.' . $extension;
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

    public function deleteBrand(Brand $brand)
    {
        // Check if brand has suppliers
        if ($brand->suppliers()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete brand "' . $brand->name . '" because it has active suppliers. Please reassign or delete the suppliers first.');
        }

        // Delete logo if exists
        if ($brand->logo && \Storage::disk('public')->exists($brand->logo)) {
            \Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();
        return redirect()->route('suppliers.index')->with('success', 'Brand deleted successfully');
    }

    public function deleteCategory(Category $category)
    {
        // 1. Check if any brand under this category has suppliers
        foreach ($category->brands as $brand) {
            if ($brand->suppliers()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete category "' . $category->name . '" because one of its brands (' . $brand->name . ') has active suppliers. Please handle those suppliers first.');
            }
        }

        // 2. Cleanup all brand logos in this category
        foreach ($category->brands as $brand) {
            if ($brand->logo && \Storage::disk('public')->exists($brand->logo)) {
                \Storage::disk('public')->delete($brand->logo);
            }
        }

        // 3. Delete Category (Brands will cascade delete in DB)
        $category->delete();

        return redirect()->route('suppliers.index')->with('success', 'Category and its associated brands have been removed successfully.');
    }

    public function deleteSupplier(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully');
    }

    public function approvals()
    {
        $pendingSuppliers = Supplier::with(['brand', 'user'])->where('status', 'pending')->latest()->get();
        $activeSuppliersCount = Supplier::where('status', 'active')->count();
        return view('backend.suppliers.approval', compact('pendingSuppliers', 'activeSuppliersCount'));
    }

    public function approve(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        // Update Supplier Status
        $supplier->update(['status' => 'active']);

        // Update User Status if exists
        $user = \App\Models\User::where('email', $supplier->email)->first();
        if ($user) {
            $user->update(['status' => 'active']);
        }

        // Send Approval Email
        $emailError = null;
        if ($supplier->email) {
            try {
                Mail::to($supplier->email)->send(new SupplierStatusMail($supplier, 'approved'));
            } catch (\Exception $e) {
                $emailError = " However, the notification email could not be sent. " . $e->getMessage();
                \Log::error("Failed to send approval email to " . $supplier->email . ": " . $e->getMessage());
            }
        }

        return redirect()->route('suppliers.approvals')->with('success', 'Supplier ' . $supplier->company_name . ' has been approved.' . $emailError);
    }

    public function deny(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        // Send Denial Email BEFORE deletion
        $emailError = null;
        if ($supplier->email) {
            try {
                Mail::to($supplier->email)->send(new SupplierStatusMail($supplier, 'denied'));
            } catch (\Exception $e) {
                $emailError = " Note: Notification email failed: " . $e->getMessage();
                \Log::error("Failed to send denial email to " . $supplier->email . ": " . $e->getMessage());
            }
        }

        // 1. Delete User & Profile Picture if exists
        $user = User::where('email', $supplier->email)->first();
        if ($user) {
            if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
                \Storage::disk('public')->delete($user->profile_picture);
            }
            $user->delete();
        }

        // 2. Delete Supplier Document if exists
        if ($supplier->document && \Storage::disk('public')->exists($supplier->document)) {
            \Storage::disk('public')->delete($supplier->document);
        }

        $companyName = $supplier->company_name;
        $supplier->delete();

        return redirect()->route('suppliers.approvals')->with('success', 'Supplier ' . $companyName . ' registration has been denied and removed.' . $emailError);
    }
}
