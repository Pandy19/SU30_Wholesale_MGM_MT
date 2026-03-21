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
            'status'   => $roleSlug === 'supplier' ? 'inactive' : 'active',
        ]);

        // 2. Handle Profile Avatar (Real file or Base64 fallback)
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        } elseif ($request->avatar_base64) {
            // Logic to save base64 if needed, but for now we focus on the supplier document below
        }

        // 3. If Supplier, Create Supplier Profile
        if ($roleSlug === 'supplier') {
            // Generate Code SUP-001...
            $lastCode = Supplier::orderBy('id', 'desc')->value('code');
            $num = ($lastCode && strlen($lastCode) >= 7) ? ((int) substr($lastCode, 4) + 1) : 1;
            $supplierCode = 'SUP-' . str_pad($num, 3, '0', STR_PAD_LEFT);

            $documentPath = null;
            if ($request->hasFile('license_doc')) {
                $documentPath = $request->file('license_doc')->store('img/SupplierLicense', 'public');
            } elseif ($request->license_base64) {
                // Optional: Decode base64 and save as file if user didn't re-upload
                $base64Data = $request->license_base64;
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                    $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                    $type = strtolower($type[1]); // jpg, png, etc
                    $base64Data = base64_decode($base64Data);
                    $fileName = 'license_' . time() . '.' . $type;
                    \Storage::disk('public')->put('img/SupplierLicense/' . $fileName, $base64Data);
                    $documentPath = 'img/SupplierLicense/' . $fileName;
                }
            }
Supplier::create([
    'code'           => $supplierCode,
    'company_name'   => $request->company_name ?? $request->name,
    'email'          => $request->email,
    'status'         => 'inactive',
    'payment_term'   => 'Net 30 Days',
    'document'       => $documentPath,
]);

return redirect()->route('admin_login.index')->with('success', 'Registration successful! Your supplier account is pending admin approval.');
}

// 4. For Admin/Staff - redirect to login instead of auto-login
return redirect()->route('admin_login.index')->with('success', 'Registration successful! Please login to continue.');
}
}
