<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index()
    {
        $users = Admin::with(['roles', 'permissions'])->paginate(500);
        return view('content.access-control.users', compact('users'));
    }

    public function create()
    {
        // Exclude super-admin role from creation to prevent uncontrolled privilege escalation
        $roles = Role::where('guard_name', 'admin-guard')->where('name', '!=', 'super-admin')->get();
        $permissions = Permission::where('guard_name', 'admin-guard')->get()->groupBy(function($p) {
            $parts = explode('-', $p->name);
            array_shift($parts);
            return count($parts) ? implode('_', $parts) : 'general';
        });
        return view('content.access-control.users-create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => [
                'required',
                'min:6',
                'regex:/^[A-Z]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
            ],
            'roles' => 'array',
            'permissions' => 'array'
        ], [
            'password.min' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
            'password.regex' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
        ]);

        // Security check: Ensure 'super-admin' isn't being manually passed in
        if ($request->has('roles') && in_array('super-admin', $request->roles)) {
            return back()->with('error', 'You cannot create new Super Admin accounts via the panel.')->withInput();
        }

        $admin = Admin::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            $admin->syncRoles($request->roles);
        }
        
        if ($request->has('permissions')) {
            $admin->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.users.index')->with('success', 'Admin User account created successfully');
    }

    public function edit($id)
    {
        $user = Admin::findOrFail($id);
        
        // Prevent editing super-admins unless you are specifically allowed or maybe just block it for safety
        if ($user->hasRole('super-admin', 'admin-guard')) {
            return redirect()->route('admin.users.index')->with('error', 'Super Admin accounts are protected and cannot be edited via the management panel.');
        }

        // Exclude super-admin role from editing
        $roles = Role::where('guard_name', 'admin-guard')->where('name', '!=', 'super-admin')->get();
        $permissions = Permission::where('guard_name', 'admin-guard')->get()->groupBy(function($p) {
            $parts = explode('-', $p->name);
            array_shift($parts);
            return count($parts) ? implode('_', $parts) : 'general';
        });
        $userRoles = $user->roles->pluck('name')->toArray();
        $userPermissions = $user->permissions->pluck('name')->toArray();
        
        return view('content.access-control.users-edit', compact('user', 'roles', 'userRoles', 'permissions', 'userPermissions'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->hasRole('super-admin', 'admin-guard')) {
            return redirect()->route('admin.users.index')->with('error', 'Super Admin accounts are protected.');
        }

        // Security check: Ensure 'super-admin' isn't being manually passed in
        if ($request->has('roles') && in_array('super-admin', $request->roles)) {
            return back()->with('error', 'You cannot assign the Super Admin role via the panel.')->withInput();
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => [
                'nullable',
                'min:6',
                'regex:/^[A-Z]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
            ],
            'roles' => 'array',
            'permissions' => 'array'
        ], [
            'password.min' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
            'password.regex' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie. Nagybetűvel kell kezdődnie, és tartalmaznia kell speciális karaktert.',
        ]);

        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();
        $admin->syncRoles($request->roles ?? []);
        $admin->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.users.index')->with('success', 'Admin User account updated successfully');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        
        // Prevent deleting super-admins
        if ($admin->hasRole('super-admin', 'admin-guard')) {
            return back()->with('error', 'Super Admin accounts are protected and cannot be deleted.');
        }

        // Prevent deleting yourself
        if ($admin->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account');
        }
        $admin->delete();
        return redirect()->route('admin.users.index')->with('success', 'Admin User account deleted successfully');
    }
}
