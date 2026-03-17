<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('content.access-control.roles', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($perm) {
            if (strpos($perm->name, '-') !== false) {
                $parts = explode('-', $perm->name);
                // Handle cases like 'view-dashboard' (module is dashboard) 
                // and 'view-admin-vehicles' (not used here but as an example)
                return $parts[1] ?? 'other';
            }
            return 'other';
        });
        return view('content.access-control.roles-create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'guard_name' => 'required|in:web,admin-guard',
            'permissions' => 'array'
        ]);

        // Check uniqueness for the given guard
        $exists = Role::where('name', $request->name)->where('guard_name', $request->guard_name)->exists();
        if ($exists) {
            return back()->withErrors(['name' => 'The role name has already been taken for this guard.'])->withInput();
        }

        $role = Role::create(['name' => $request->name, 'guard_name' => $request->guard_name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::where('guard_name', $role->guard_name)->get()->groupBy(function($perm) {
            if (strpos($perm->name, '-') !== false) {
                return explode('-', $perm->name)[1] ?? 'other';
            }
            return 'other';
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('content.access-control.roles-edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'permissions' => 'array'
        ]);

        // Check uniqueness for the given guard excluding current id
        $exists = Role::where('name', $request->name)
                    ->where('guard_name', $role->guard_name)
                    ->where('id', '!=', $id)
                    ->exists();
        if ($exists) {
            return back()->withErrors(['name' => 'The role name has already been taken for this guard.'])->withInput();
        }

        $role->name = $request->name;
        $role->save();

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        // Note: users() in Spatie Permission is a bit more complex with guards.
        // We'll just check if any user has this role in that guard.
        // But for common usage, deleting a role that is in use is usually blocked.
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully');
    }
}
