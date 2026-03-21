<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class admin_loginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }
        return view('backend.admin_login.index');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
            'role'     => ['required'],
        ]);

        $role_selected = $request->role;

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // 1. Verify Role Match
            if ($user->role !== $role_selected) {
                Auth::logout();
                return back()->with('error', 'Role mismatch. The account you are trying to access does not have the "' . ucfirst($role_selected) . '" role.');
            }

            // 2. Verify Status
            if ($user->status === 'inactive') {
                Auth::logout();
                return back()->with('error', 'Your account is inactive or pending approval.');
            }

            $request->session()->regenerate();

            return redirect()->intended('/')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin_login.index')->with('success', 'Logged out successfully!');
    }
}
