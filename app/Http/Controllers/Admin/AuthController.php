<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Show login form
    public function index()
    {
        if (Auth::check()) {
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

        // Attempt login
        if (Auth::attempt($data)) {

          // Check if logged-in user has admin role
            if (!Auth::user()->hasRole('admin')) {
                Auth::logout();
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
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
