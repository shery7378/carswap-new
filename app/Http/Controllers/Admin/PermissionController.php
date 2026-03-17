<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('content.access-control.permissions', compact('permissions'));
    }

    public function create()
    {
        return view('content.access-control.permissions-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'guard_name' => 'required|in:web,admin-guard'
        ]);

        // Check uniqueness for the given guard
        $exists = Permission::where('name', $request->name)
                            ->where('guard_name', $request->guard_name)
                            ->exists();
        if ($exists) {
            return back()->withErrors(['name' => 'The permission name has already been taken for this guard.'])->withInput();
        }

        Permission::create([
            'name' => $request->name, 
            'guard_name' => $request->guard_name
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('content.access-control.permissions-edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $request->validate([
            'name' => 'required'
        ]);

        // Check uniqueness for the given guard
        $exists = Permission::where('name', $request->name)
                            ->where('guard_name', $permission->guard_name)
                            ->where('id', '!=', $id)
                            ->exists();
        if ($exists) {
            return back()->withErrors(['name' => 'The permission name has already been taken for this guard.'])->withInput();
        }

        $permission->name = $request->name;
        $permission->save();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully');
    }
}
