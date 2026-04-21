<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('content.Admin.forgot-password');
    }

    /**
     * Send a reset link to the given user
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:admins,email']);

        $token = Str::random(60);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('admin.password.reset', ['token' => $token]) . '?email=' . urlencode($request->email);

        try {
            Mail::send([], [], function ($message) use ($request, $resetUrl) {
                $message->to($request->email)
                    ->subject('Admin Password Reset Request')
                    ->html("<h3>Reset Your Admin Password</h3><p>Click the link below to reset your password:</p><p><a href='{$resetUrl}'>{$resetUrl}</a></p>");
            });
        } catch (\Exception $e) {
            Log::error('Mail Error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send reset email. Please check your mail settings.']);
        }

        return back()->with('status', 'We have e-mailed your password reset link!');
    }

    /**
     * Show the reset password form
     */
    public function showResetPasswordForm(Request $request, $token)
    {
        return view('content.Admin.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Reset the given user's password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:admins,email',
            'password' => [
                'required',
                'confirmed',
                'min:6',
                'regex:/^[A-Z]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
            ],
        ], [
            'password.min' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
            'password.regex' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return back()->withErrors(['email' => 'This password reset token is invalid.']);
        }

        if (now()->parse($reset->created_at)->addHour()->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'This password reset token has expired.']);
        }

        $admin = \App\Models\Admin::where('email', $request->email)->first();
        $admin->password = Hash::make($request->password);
        $admin->save();

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Your password has been reset!');
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
