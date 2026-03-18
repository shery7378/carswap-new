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
        $users = Admin::with(['roles', 'permissions'])->paginate(10);
        return view('content.access-control.users', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('guard_name', 'admin-guard')->get();
        $permissions = Permission::where('guard_name', 'admin-guard')->get();
        return view('content.access-control.users-create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8',
            'roles' => 'array',
            'permissions' => 'array'
        ]);

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

        return redirect()->route('admin.users.index')->with('success', 'Administrator account created successfully');
    }

    public function edit($id)
    {
        $user = Admin::findOrFail($id);
        
        // Prevent editing super-admins unless you are specifically allowed or maybe just block it for safety
        if ($user->hasRole('super-admin', 'admin-guard')) {
            return redirect()->route('admin.users.index')->with('error', 'Super Admin accounts are protected and cannot be edited via the management panel.');
        }

        $roles = Role::where('guard_name', 'admin-guard')->get();
        $permissions = Permission::where('guard_name', 'admin-guard')->get();
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

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|min:8',
            'roles' => 'array',
            'permissions' => 'array'
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

        return redirect()->route('admin.users.index')->with('success', 'Administrator account updated successfully');
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
        return redirect()->route('admin.users.index')->with('success', 'Administrator account deleted successfully');
    }
}
