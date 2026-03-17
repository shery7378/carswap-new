<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // --- CREATE ROLES IF THEY DON'T EXIST (with admin-guard) ---
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'admin-guard']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin-guard']);
        $subAdminRole = Role::firstOrCreate(['name' => 'sub-admin', 'guard_name' => 'admin-guard']);

        // --- CREATE SUPER-ADMIN ---
        $superAdmin = Admin::firstOrCreate(
            ['email' => 'super-admin@example.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );
        if (!$superAdmin->hasRole('super-admin', 'admin-guard')) {
            $superAdmin->assignRole($superAdminRole);
        }

        // --- CREATE ADMIN ---
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password'),
            ]
        );
        if (!$admin->hasRole('admin', 'admin-guard')) {
            $admin->assignRole($adminRole);
        }

        // --- CREATE SUB-ADMIN ---
        $subAdmin = Admin::firstOrCreate(
            ['email' => 'sub-admin@example.com'],
            [
                'first_name' => 'Sub',
                'last_name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );
        if (!$subAdmin->hasRole('sub-admin', 'admin-guard')) {
            $subAdmin->assignRole($subAdminRole);
        }
    }
}
