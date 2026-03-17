<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show login form
    public function index()
    {
        if (Auth::guard('admin-guard')->check()) {
            return redirect()->route('dashboard-analytics');
        }
        return view('content.Admin.admin-login');
    }

    // Handle login
    public function store(Request $request)
    {
        // Validate form data
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Attempt login with admin-guard
        if (Auth::guard('admin-guard')->attempt($data)) {
            // Check for admin-level roles
            $admin = Auth::guard('admin-guard')->user();
            if (!$admin->hasAnyRole(['super-admin', 'admin', 'sub-admin'])) {
                Auth::guard('admin-guard')->logout();
                return back()->withErrors(['email' => 'Unauthorized access']);
            }

            // Redirect to admin dashboard
            return redirect()->route('dashboard-analytics');
        }

        // Login failed
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('admin-guard')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
