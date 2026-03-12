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

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign existing permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo(['access-frontend-pages', 'view-dashboard']);

        $dealerRole = Role::firstOrCreate(['name' => 'dealer', 'guard_name' => 'web']);
        $dealerRole->givePermissionTo(['access-frontend-pages', 'manage-vehicles']);

        $subscriberRole = Role::firstOrCreate(['name' => 'subscriber', 'guard_name' => 'web']);
        $subscriberRole->givePermissionTo(['access-frontend-pages']);
    }
}
