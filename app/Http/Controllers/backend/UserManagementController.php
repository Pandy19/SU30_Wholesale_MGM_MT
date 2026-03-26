<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $perPage = $request->get('per_page', 10);

        // Base query for statistics (excluding suppliers and owners)
        $baseStatsQuery = User::whereNotIn('role', ['supplier', 'owner']);
        
        $stats = [
            'total'    => (clone $baseStatsQuery)->count(),
            'active'   => (clone $baseStatsQuery)->where('status', 'active')->count(),
            'pending'  => (clone $baseStatsQuery)->where('status', 'pending')->count(),
            'inactive' => (clone $baseStatsQuery)->where('status', 'inactive')->count(),
        ];

        $query = User::query();
        // Hide Suppliers and Owners from the list, and hide inactive users
        $query->whereNotIn('role', ['supplier', 'owner'])
              ->where('status', '!=', 'inactive');

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
        
        // Security: Admins cannot create or assign Admin role to others
        if ($currentUser->role === 'admin') {
            $rolesQuery->where('slug', '!=', 'admin');
        }
        
        $roles = $rolesQuery->orderBy('name')->get();

        if ($request->ajax()) {
            return view('backend.user_management.table', compact('users'))->render();
        }

        return view('backend.user_management.index', compact('users', 'roles', 'stats'));
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

        $userCode = $this->generateUserCode($roleSlug);

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
            $fileName = Str::slug($request->name) . '-' . $roleSlug . '-' . now()->format('Ymd_His') . '.' . $extension;
            
            // Dynamic Path based on role
            $folder = $roleSlug . '_profile'; // e.g., staff_profile, accountant_profile
            
            $avatarPath = $request->file('avatar')->storeAs($folder, $fileName, 'public');
            $user->update(['profile_picture' => $avatarPath]);
        }

        return redirect()->route('user_management.index')->with('success', 'User created successfully!');
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
            // Example: Staff-001-MMO -> parts: [Staff, 001, MMO]
            $parts = explode('-', $lastUserWithPrefix->user_code);
            if (count($parts) >= 2 && is_numeric($parts[1])) {
                $nextNumber = intval($parts[1]) + 1;
            }
        }
        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT) . '-MMO';
    }

    public function downloadTemplate()
    {
        $filename = "user_import_template.csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // CSV Header
        fputcsv($handle, ['name', 'email', 'role', 'password']);
        
        // Example Row
        fputcsv($handle, ['John Doe', 'john@example.com', 'staff', 'password123']);
        fputcsv($handle, ['Jane Smith', 'jane@example.com', 'accountant', 'password123']);
        
        fclose($handle);
        exit;
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), 'r');
        
        // Skip header
        $header = fgetcsv($handle);
        
        $importedCount = 0;
        $errors = [];
        $rowNum = 1;

        while (($data = fgetcsv($handle)) !== FALSE) {
            $rowNum++;
            if (count($data) < 4) {
                $errors[] = "Row {$rowNum}: Incomplete data.";
                continue;
            }

            $name = trim($data[0]);
            $email = trim($data[1]);
            $roleSlug = strtolower(trim($data[2]));
            $password = trim($data[3]);

            // Validation
            if (empty($name) || empty($email) || empty($roleSlug) || empty($password)) {
                $errors[] = "Row {$rowNum}: All fields are required.";
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row {$rowNum}: Invalid email format ({$email}).";
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $errors[] = "Row {$rowNum}: Email already exists ({$email}).";
                continue;
            }

            $role = Role::where('slug', $roleSlug)->first();
            if (!$role) {
                $errors[] = "Row {$rowNum}: Role '{$roleSlug}' does not exist.";
                continue;
            }

            // Security: Admins cannot import owners or other admins
            $currentUser = auth()->user();
            if ($currentUser->role === 'admin' && in_array($roleSlug, ['owner', 'admin'])) {
                $errors[] = "Row {$rowNum}: Unauthorized to create '{$roleSlug}' role.";
                continue;
            }

            if (strlen($password) < 8) {
                $errors[] = "Row {$rowNum}: Password must be at least 8 characters.";
                continue;
            }

            try {
                $userCode = $this->generateUserCode($roleSlug);
                
                User::create([
                    'name'      => $name,
                    'email'     => $email,
                    'password'  => Hash::make($password),
                    'role'      => $roleSlug,
                    'role_id'   => $role->id,
                    'status'    => 'pending',
                    'user_code' => $userCode,
                ]);
                $importedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNum}: " . $e->getMessage();
            }
        }

        fclose($handle);

        $message = "{$importedCount} users imported successfully.";
        if (!empty($errors)) {
            $message .= " Errors found in " . count($errors) . " rows.";
            return redirect()->route('user_management.index')
                ->with('success', $message)
                ->with('import_errors', $errors);
        }

        return redirect()->route('user_management.index')->with('success', $message);
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

        // Security check: Admins cannot assign Owner or Admin roles to others
        // Allow if it's the same role they already have (just updating profile)
        if ($currentUser->role === 'admin' && in_array($roleSlug, ['owner', 'admin']) && $user->role !== $roleSlug) {
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
            $fileName = Str::slug($request->name) . '-' . $roleSlug . '-' . now()->format('Ymd_His') . '.' . $extension;
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
            return back()->with('error', 'You cannot deactivate yourself!');
        }

        // Set status to inactive instead of deleting
        $user->update(['status' => 'inactive']);

        return redirect()->route('user_management.index')->with('success', 'User deactivated successfully!');
    }
}
