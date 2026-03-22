<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\Role;

class admin_registerController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('name')->get();
        return view('backend.admin_register.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'role_id'  => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $roleObj = Role::findOrFail($request->role_id);
        $roleSlug = $roleObj->slug;

        // 1. Create User
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $roleSlug, // Backward compatibility
            'role_id'  => $request->role_id,
            'status'   => $roleSlug === 'supplier' ? 'pending' : 'active',
        ]);

        // 2. Handle Profile Avatar (Real file or Base64 fallback)
        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $extension = $request->file('avatar')->getClientOriginalExtension();
            
            if ($roleSlug === 'supplier') {
                // Specific format for Suppliers
                $namePrefix = $request->contact_person ?: $request->name;
                $fileName = $namePrefix . '_' . now()->format('Ymd_His') . '.' . $extension;
                $avatarPath = $request->file('avatar')->storeAs('supplier_profile', $fileName, 'public');
            } else {
                // Admin or Staff format: Full Name-Date
                $fileName = $request->name . '-' . now()->format('Ymd_His') . '.' . $extension;
                $avatarPath = $request->file('avatar')->storeAs('admin_profile', $fileName, 'public');
            }
        } elseif ($request->avatar_base64) {
            $base64Data = $request->avatar_base64;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $match)) {
                $extension = strtolower($match[1]);
                $base64Content = base64_decode(substr($base64Data, strpos($base64Data, ',') + 1));
                
                if ($roleSlug === 'supplier') {
                    $namePrefix = $request->contact_person ?: $request->name;
                    $fileName = $namePrefix . '_' . now()->format('Ymd_His') . '.' . $extension;
                    \Storage::disk('public')->put('supplier_profile/' . $fileName, $base64Content);
                    $avatarPath = 'supplier_profile/' . $fileName;
                } else {
                    $fileName = $request->name . '-' . now()->format('Ymd_His') . '.' . $extension;
                    \Storage::disk('public')->put('admin_profile/' . $fileName, $base64Content);
                    $avatarPath = 'admin_profile/' . $fileName;
                }
            }
        }

        if ($avatarPath) {
            $user->update(['profile_picture' => $avatarPath]);
        }

        // 3. If Supplier, Create Supplier Profile
        if ($roleSlug === 'supplier') {
            // Generate Code SUP-001...
            $lastCode = Supplier::orderBy('id', 'desc')->value('code');
            $num = ($lastCode && strlen($lastCode) >= 7) ? ((int) substr($lastCode, 4) + 1) : 1;
            $supplierCode = 'SUP-' . str_pad($num, 3, '0', STR_PAD_LEFT);

            $documentPath = null;
            if ($request->hasFile('license_doc')) {
                $extension = $request->file('license_doc')->getClientOriginalExtension();
                $fileName = $supplierCode . '-' . $request->name . '.' . $extension;
                $documentPath = $request->file('license_doc')->storeAs('img/SupplierLicense', $fileName, 'public');
            } elseif ($request->license_base64) {
                $base64Data = $request->license_base64;
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                    $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                    $type = strtolower($type[1]);
                    $base64Data = base64_decode($base64Data);
                    $fileName = $supplierCode . '-' . $request->name . '.' . $type;
                    \Storage::disk('public')->put('img/SupplierLicense/' . $fileName, $base64Data);
                    $documentPath = 'img/SupplierLicense/' . $fileName;
                }
            }
Supplier::create([
    'code'           => $supplierCode,
    'company_name'   => $request->name, // Registration name is the company name
    'contact_person' => $request->contact_person,
    'phone'          => $request->phone,
    'email'          => $request->email,
    'address'        => $request->address,
    'payment_term'   => $request->payment_term ?? 'Net 30 Days',
    'lead_time_days' => $request->lead_time_days ?? 7,
    'status'         => 'pending',
    'document'       => $documentPath,
]);

return redirect()->route('admin_login.index')->with('success', 'Registration successful! Your supplier account is pending admin approval.');
}

// 4. For Admin/Staff - redirect to login instead of auto-login
return redirect()->route('admin_login.index')->with('success', 'Registration successful! Please login to continue.');
}
}
