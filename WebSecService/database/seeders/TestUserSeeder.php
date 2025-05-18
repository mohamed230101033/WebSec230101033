<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Test User with the Customer role
        $testUser = User::firstOrCreate(
            ['email' => 'testuser@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
                'admin' => false,
                'email_verified_at' => now(),
                'credit' => 100.00,
                'phone' => '+201234567893',
            ]
        );
        
        // Ensure Customer role exists
        $customerRole = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);
        
        // Ensure select_favourite permission exists
        $favouritePermission = Permission::firstOrCreate([
            'name' => 'select_favourite', 
            'guard_name' => 'web'
        ]);
        
        // Ensure customer role has the select_favourite permission
        if (!$customerRole->hasPermissionTo('select_favourite')) {
            $customerRole->givePermissionTo($favouritePermission);
        }
        
        // Assign customer role to Test User
        $testUser->assignRole($customerRole);
        
        // Ensure Test User has required permissions
        $requiredPermissions = [
            'view_products',
            'purchase_products',
            'view_own_profile',
            'select_favourite'
        ];
        
        foreach ($requiredPermissions as $permissionName) {
            $permission = Permission::firstOrCreate([
                'name' => $permissionName, 
                'guard_name' => 'web'
            ]);
            
            if (!$testUser->hasPermissionTo($permissionName)) {
                $testUser->givePermissionTo($permission);
            }
        }
    }
} 