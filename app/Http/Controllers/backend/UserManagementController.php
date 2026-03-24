<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        $currentUser = auth()->user();
        $perPage = $request->get('per_page', 10);

        // Hide Suppliers and Owners from the list
        $query->whereNotIn('role', ['supplier', 'owner']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('user_code', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Roles for the dropdown - Exclude Supplier and Owner roles from internal management
        $rolesQuery = Role::query();
        $rolesQuery->whereNotIn('slug', ['supplier', 'owner']);
        
        $roles = $rolesQuery->orderBy('name')->get();

        if ($request->ajax()) {
            return view('backend.user_management.table', compact('users'))->render();
        }

        return view('backend.user_management.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'role_id'  => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $roleObj = Role::findOrFail($request->role_id);
        $roleSlug = $roleObj->slug;
        $currentUser = auth()->user();

        // Security check: Admins cannot create owners or admins
        if ($currentUser->role === 'admin' && in_array($roleSlug, ['owner', 'admin'])) {
            return back()->with('error', 'Unauthorized: Admins cannot create Owners or other Admins.');
        }

        // Generate User Code: RoleName-001-MMO
        $roleName = ucfirst($roleSlug);
        $lastUserWithRole = User::where('role', $roleSlug)->orderBy('id', 'desc')->first();
        
        $nextNumber = 1;
        if ($lastUserWithRole && $lastUserWithRole->user_code) {
            // Extract the number from Role-XXX-MMO
            $parts = explode('-', $lastUserWithRole->user_code);
            if (count($parts) >= 2) {
                $nextNumber = intval($parts[1]) + 1;
            }
        }
        $userCode = $roleName . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT) . '-MMO';

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $roleSlug,
            'role_id'   => $request->role_id,
            'status'    => 'pending', // Set to pending by default
            'user_code' => $userCode,
        ]);

        if ($request->hasFile('avatar')) {
            $extension = $request->file('avatar')->getClientOriginalExtension();
            
            // Format: name-role-date
            $fileName = str_replace(' ', '_', strtolower($request->name)) . '-' . $roleSlug . '-' . now()->format('Ymd_His') . '.' . $extension;
            
            // Dynamic Path based on role
            $folder = $roleSlug . '_profile'; // e.g., staff_profile, accountant_profile
            
            $avatarPath = $request->file('avatar')->storeAs($folder, $fileName, 'public');
            $user->update(['profile_picture' => $avatarPath]);
        }

        return redirect()->route('user_management.index')->with('success', 'User created successfully!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'role_id'  => 'required|exists:roles,id',
            'status'   => 'required|in:active,inactive,pending',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $roleObj = Role::findOrFail($request->role_id);
        $roleSlug = $roleObj->slug;
        $currentUser = auth()->user();

        // Security check
        if ($currentUser->role === 'admin' && in_array($roleSlug, ['owner', 'admin'])) {
            return back()->with('error', 'Unauthorized: Admins cannot assign Owner or Admin roles.');
        }

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'role'    => $roleSlug,
            'role_id' => $request->role_id,
            'status'  => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $extension = $request->file('avatar')->getClientOriginalExtension();
            $fileName = str_replace(' ', '_', strtolower($request->name)) . '-' . $roleSlug . '-' . now()->format('Ymd_His') . '.' . $extension;
            $folder = $roleSlug . '_profile';
            
            $avatarPath = $request->file('avatar')->storeAs($folder, $fileName, 'public');
            $data['profile_picture'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('user_management.index')->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself!');
        }

        // Delete profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();

        return redirect()->route('user_management.index')->with('success', 'User deleted successfully!');
    }
}
