<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions categories
        $modules = ['vehicles', 'users', 'roles', 'subscriptions', 'orders', 'partners', 'inquiries'];
        $actions = ['view', 'create', 'edit', 'delete'];

        $allPermissions = [
            'view-dashboard',
            'access-frontend-pages',
        ];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $allPermissions[] = "{$action}-{$module}";
            }
        }

        // Create permissions for both guards
        foreach ($allPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'admin-guard']);
        }

        // --- ADMIN GUARD ROLES ---
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'admin-guard']);
        $superAdminRole->syncPermissions(Permission::where('guard_name', 'admin-guard')->get());

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin-guard']);
        $adminRole->syncPermissions(Permission::where('guard_name', 'admin-guard')->get());

        $subAdminRole = Role::firstOrCreate(['name' => 'sub-admin', 'guard_name' => 'admin-guard']);
        $subAdminRole->syncPermissions(
            Permission::where('guard_name', 'admin-guard')
                ->where(function ($q) {
                    $q->where('name', 'view-dashboard')
                      ->orWhere('name', 'LIKE', 'view-%')
                      ->orWhere('name', 'LIKE', 'edit-vehicles%')
                      ->orWhere('name', 'LIKE', 'create-vehicles%');
                })
                ->get()
        );

        // --- WEB GUARD ROLES (for regular users) ---
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->syncPermissions(
            Permission::where('guard_name', 'web')
                ->whereIn('name', ['access-frontend-pages', 'view-dashboard'])
                ->get()
        );

        $dealerRole = Role::firstOrCreate(['name' => 'dealer', 'guard_name' => 'web']);
        $dealerRole->syncPermissions(
            Permission::where('guard_name', 'web')
                ->whereIn('name', ['access-frontend-pages', 'manage-vehicles'])
                ->get()
        );

        $subscriberRole = Role::firstOrCreate(['name' => 'subscriber', 'guard_name' => 'web']);
        $subscriberRole->syncPermissions(
            Permission::where('guard_name', 'web')
                ->where('name', 'access-frontend-pages')
                ->get()
        );
    }
}
