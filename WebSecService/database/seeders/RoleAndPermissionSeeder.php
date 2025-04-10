<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'add_products' => 'Add Products',
            'edit_products' => 'Edit Products',
            'delete_products' => 'Delete Products',
            'show_users' => 'Show Users',
            'edit_users' => 'Edit Users',
            'delete_users' => 'Delete Users',
            'admin_users' => 'Administer Users',
            'view_products' => 'View Products',
            'purchase_products' => 'Purchase Products',
            'view_own_profile' => 'View Own Profile',
            'manage_stock' => 'Manage Stock',
            'manage_customer_credit' => 'Manage Customer Credit',
            'hold_products' => 'Hold Products',
        ];

        foreach ($permissions as $permission => $description) {
            // Check if permission exists before creating it
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission, 'description' => $description]);
            }
        }

        // Create roles if they don't exist
        // Admin role
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Employee role
        $employeeRole = Role::firstOrCreate(['name' => 'Employee']);
        $employeePermissions = [
            'add_products', 'edit_products', 'show_users', 'edit_users',
            'view_products', 'manage_stock', 'manage_customer_credit', 'hold_products'
        ];
        $employeeRole->syncPermissions($employeePermissions);

        // Customer role
        $customerRole = Role::firstOrCreate(['name' => 'Customer']);
        $customerPermissions = [
            'view_products', 'purchase_products', 'view_own_profile'
        ];
        $customerRole->syncPermissions($customerPermissions);
    }
} 