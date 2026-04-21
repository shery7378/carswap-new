<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class User_Controller extends Controller
{
    public function index()
    {
        $users = User::orderBy("created_at", "desc")->paginate(500);
        return view('content.apps.users.list', compact('users'));
    }

    public function view($id, Request $request)
    {
        $user = User::with(['subscription'])->findOrFail($id);

        // Fetch dynamic stats
        $totalPostings = \App\Models\Vehicle::where('user_id', $user->id)->count();
        $totalFavorites = $user->favorites()->count();
        $activePlan = $user->activeSubscription->plan->name ?? 'Free Tier';
        $recentVehicles = \App\Models\Vehicle::where('user_id', $user->id)->latest()->limit(5)->get();

        if ($request->ajax()) {
            return view('content.apps.users.partials.show-modal-content', compact('user', 'totalPostings', 'totalFavorites', 'activePlan', 'recentVehicles'));
        }

        return view('content.apps.users.view', compact('user', 'totalPostings', 'totalFavorites', 'activePlan', 'recentVehicles'));
    }

    public function create()
    {
        return view('content.apps.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'password' => [
                'required',
                'min:6',
                'regex:/^[A-Z]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
            ],
            'status' => 'required|in:active,inactive,banned',
        ], [
            'password.min' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
            'password.regex' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.web-users.index')->with('success', 'User created successfully');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('content.apps.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string',
            'status' => 'required|in:active,inactive,banned',
        ]);

        $user->update($request->only(['first_name', 'last_name', 'email', 'phone', 'status']));

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('admin.web-users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.web-users.index')->with('success', 'User deleted successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate(['status' => 'required|in:active,inactive,banned']);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        $user = User::where('email', $request->email)->first();
        $token = \Illuminate\Support\Str::random(60);

        \Illuminate\Support\Facades\DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => \Illuminate\Support\Facades\Hash::make($token),
                'created_at' => now(),
            ]
        );

        try {
            \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\ResetPasswordMail($user, $token));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin Reset Link Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to send reset link. Please check mail settings.');
        }

        return back()->with('success', 'Password reset link sent to user email.');
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^[A-Z]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
            ],
        ], [
            'password.min' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
            'password.regex' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
        ]);

        $user = User::findOrFail($id);
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully for ' . $user->first_name
        ]);
    }
}
