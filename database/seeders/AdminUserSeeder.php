<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin role if not exists
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // Create admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password'),
            ]
        );

        // Assign role to user
        if (!$user->hasRole('admin')) {
            $user->assignRole($role);
        }
    }
}
