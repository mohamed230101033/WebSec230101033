<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        $customerRole = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);
        
        // Create permissions - using the exact permission names from the database
        $permissions = [
            'add_products',
            'edit_products',
            'delete_products',
            'show_users',
            'edit_users',
            'admin_users',
            'delete_users',
            'view_products',
            'purchase_products',
            'view_own_profile',
            'manage_stock',
            'manage_customer_credit',
            'hold_products'
        ];
        
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }
        
        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        
        $employeeRole->givePermissionTo([
            'add_products',
            'edit_products',
            'view_products',
            'manage_stock',
            'view_own_profile',
            'show_users',
            'hold_products'
        ]);
        
        $customerRole->givePermissionTo([
            'purchase_products',
            'view_products',
            'view_own_profile'
        ]);
        
        // Create Admin user with 0.00 credit
        $admin = User::firstOrCreate(
            ['email' => 'mohamedamrr666@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Qwe!2345'),
                'admin' => true,
                'email_verified_at' => now(),
                'credit' => 0.00
            ]
        );
        $admin->assignRole($adminRole);
        
        // Create Employee user with 0.00 credit
        $employee = User::firstOrCreate(
            ['email' => 'mohamedamrr774@gmail.com'],
            [
                'name' => 'Employee User',
                'password' => Hash::make('Qwe!2345'),
                'admin' => false,
                'email_verified_at' => now(),
                'credit' => 0.00
            ]
        );
        $employee->assignRole($employeeRole);
        
        // Create Customer user
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('Qwe!2345'),
                'admin' => false,
                'email_verified_at' => now(),
                'credit' => 200.00
            ]
        );
        $customer->assignRole($customerRole);
        
        // Create Products based on the images provided
        $products = [
            [
                'name' => '32" LED TV',
                'code' => 'TV001',
                'model' => '32" Basic LED',
                'price' => 249.99,
                'description' => '32-inch LED TV with 2 HDMI ports and 2 USB ports, vibrant color display',
                'stock' => 5,
                'hold' => false,
                'photo' => 'tv.jpg'
            ],
            [
                'name' => 'Gray Sofa Cover',
                'code' => 'SC001',
                'model' => 'Textured Gray',
                'price' => 59.99,
                'description' => 'Stretchable gray sofa cover with skirt, fits standard 3-seater sofas',
                'stock' => 12,
                'hold' => false,
                'photo' => 'sofa.jpg'
            ],
            [
                'name' => 'Double Door Refrigerator',
                'code' => 'RF001',
                'model' => 'Silver Compact',
                'price' => 399.99,
                'description' => 'Compact silver double-door refrigerator, perfect for small apartments',
                'stock' => 3,
                'hold' => false,
                'photo' => 'fridge.jpg'
            ]
        ];
        
        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['code' => $productData['code']],
                $productData
            );
        }
    }
}
