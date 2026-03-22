<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Support\Str;

use App\Models\AuditLog;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $supplier = null;
        if ($user->role === 'supplier' || ($user->role_id && \App\Models\Role::find($user->role_id)->slug === 'supplier')) {
            $supplier = Supplier::where('email', $user->email)->first();
        }
        
        // Fetch real Audit Logs (Last 100)
        $logs = AuditLog::where('user_id', $user->id)->latest()->take(100)->get();
        
        return view('backend.setting.index', compact('user', 'supplier', 'logs'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update Name
        $user->name = $request->name;

        // Update Profile Picture
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $extension = $request->file('profile_picture')->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');
            
            if ($user->role === 'supplier') {
                $supplier = Supplier::where('email', $user->email)->first();
                $namePrefix = $supplier ? $supplier->contact_person : $user->name;
                $fileName = $namePrefix . '_' . $timestamp . '.' . $extension;
                $path = $request->file('profile_picture')->storeAs('supplier_profile', $fileName, 'public');
            } else {
                // Admin or Staff format: Full Name-Date
                $fileName = $user->name . '-' . $timestamp . '.' . $extension;
                $path = $request->file('profile_picture')->storeAs('admin_profile', $fileName, 'public');
            }
            
            $user->profile_picture = $path;
        }

        // Update Password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // If supplier, sync company name if name was changed? 
        // Usually name in Users for supplier is the Company Name in this project
        if ($user->role === 'supplier') {
            $supplier = Supplier::where('email', $user->email)->first();
            if ($supplier) {
                $supplier->update(['company_name' => $request->name]);
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
