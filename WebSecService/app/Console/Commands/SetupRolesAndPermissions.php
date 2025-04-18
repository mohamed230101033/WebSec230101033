<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SetupRolesAndPermissions extends Command
{
    protected $signature = 'setup:roles-permissions';
    protected $description = 'Set up roles and permissions and assign them to users';

    public function handle()
    {
        $this->info('Setting up roles and permissions...');

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee']);
        $customerRole = Role::firstOrCreate(['name' => 'Customer']);

        // Create permissions
        $permissions = [
            'edit_products',
            'delete_products',
            'hold_products',
            'manage_stock',
            'purchase_products',
            'manage_users',
            'view_reports',
            'access_admin_panel',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        
        $employeeRole->givePermissionTo([
            'edit_products',
            'hold_products',
            'manage_stock',
            'view_reports'
        ]);
        
        $customerRole->givePermissionTo([
            'purchase_products'
        ]);

        // Find or create admin user if it doesn't exist
        $adminUser = User::where('email', 'mohamedamrr666@gmail.com')->first();
        
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin',
                'email' => 'mohamedamrr666@gmail.com',
                'password' => bcrypt('Qwe!2345'),
                'admin' => true,
                'email_verified_at' => now(),
            ]);
            $this->info('Admin user created with email: mohamedamrr666@gmail.com and password: Qwe!2345');
        } else {
            $this->info('Admin user already exists: ' . $adminUser->email);
        }
        
        // Find or create employee user if it doesn't exist
        $employeeUser = User::where('email', 'mohamedamrr774@gmail.com')->first();
        
        if (!$employeeUser) {
            $employeeUser = User::create([
                'name' => 'Employee',
                'email' => 'mohamedamrr774@gmail.com',
                'password' => bcrypt('Qwe!2345'),
                'admin' => false,
                'email_verified_at' => now(),
            ]);
            $this->info('Employee user created with email: mohamedamrr774@gmail.com and password: Qwe!2345');
        } else {
            $this->info('Employee user already exists: ' . $employeeUser->email);
        }

        // Assign roles to users (will replace any existing roles)
        $adminUser->syncRoles([$adminRole]);
        $this->info('Assigned Admin role to: ' . $adminUser->email);
        
        $employeeUser->syncRoles([$employeeRole]);
        $this->info('Assigned Employee role to: ' . $employeeUser->email);

        // Assign an example customer role
        $customers = User::where('email', '!=', 'mohamedamrr774@gmail.com')
                          ->where('email', '!=', 'mohamedamrr666@gmail.com')
                          ->get();
        
        foreach ($customers as $customer) {
            $customer->syncRoles([$customerRole]);
            $this->info("Assigned Customer role to: {$customer->email}");
        }

        $this->info('Roles and permissions have been set up successfully!');
    }
} 