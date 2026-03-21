<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class customersController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('customer_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->orderBy('id', 'desc')->paginate(10);
        
        $totalCustomers = Customer::count();
        $b2bCount = Customer::where('type', 'B2B')->count();
        $b2cCount = Customer::where('type', 'B2C')->count();
        
        return view('backend.customers.index', compact('customers', 'totalCustomers', 'b2bCount', 'b2cCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:B2B,B2C',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'payment_terms' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,on_hold,blacklisted',
        ]);

        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/customers'), $filename);
            $profilePicturePath = 'uploads/customers/' . $filename;
        }

        // Auto-generate customer code if not provided
        $count = Customer::count() + 1;
        $customerCode = 'CUS-' . $request->type . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        Customer::create([
            'customer_code' => $customerCode,
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'type' => $request->type,
            'phone' => $request->phone,
            'email' => $request->email,
            'profile_picture' => $profilePicturePath,
            'address' => $request->address,
            'tax_number' => $request->tax_number,
            'payment_terms' => $request->payment_terms,
            'credit_limit' => $request->credit_limit,
            'status' => $request->status,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:B2B,B2C',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'payment_terms' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,on_hold,blacklisted',
        ]);

        $data = $request->except(['_token', '_method', 'profile_picture']);

        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($customer->profile_picture && file_exists(public_path($customer->profile_picture))) {
                unlink(public_path($customer->profile_picture));
            }
            
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/customers'), $filename);
            $data['profile_picture'] = 'uploads/customers/' . $filename;
        }

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        
        // Delete profile picture if exists
        if ($customer->profile_picture && file_exists(public_path($customer->profile_picture))) {
            unlink(public_path($customer->profile_picture));
        }
        
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
