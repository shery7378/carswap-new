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

        // Create permissions
        $permissions = [
            'manage-vehicles',
            'manage-users',
            'manage-roles',
            'manage-subscriptions',
            'manage-orders',
            'view-dashboard',
            'access-frontend-pages', // Specific for your Next.js requirement
        ];

        // Create permissions for both guards
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin-guard']);
        }

        // --- ADMIN GUARD ROLES ---
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'admin-guard']);
        $superAdminRole->syncPermissions(Permission::where('guard_name', 'admin-guard')->get());

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin-guard']);
        $adminRole->syncPermissions(Permission::where('guard_name', 'admin-guard')->get());

        $subAdminRole = Role::firstOrCreate(['name' => 'sub-admin', 'guard_name' => 'admin-guard']);
        $subAdminRole->syncPermissions(
            Permission::where('guard_name', 'admin-guard')
                ->whereIn('name', ['view-dashboard', 'manage-vehicles', 'manage-orders'])
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
