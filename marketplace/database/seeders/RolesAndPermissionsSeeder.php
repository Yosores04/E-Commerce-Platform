<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Product permissions
            'view products',
            'create products',
            'edit products',
            'delete products',
            
            // Order permissions
            'view orders',
            'create orders',
            'edit orders',
            'cancel orders',
            'refund orders',
            
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            'ban users',
            
            // Vendor permissions
            'view vendors',
            'approve vendors',
            'suspend vendors',
            'edit vendors',
            
            // Category permissions
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            
            // Review permissions
            'view reviews',
            'moderate reviews',
            'delete reviews',
            
            // Report permissions
            'view reports',
            'view analytics',
            
            // Settings permissions
            'manage settings',
            'manage payments',
            'manage shipping',
            
            // Coupon permissions
            'view coupons',
            'create coupons',
            'edit coupons',
            'delete coupons',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and assign permissions

        // Super Admin - all permissions
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - most permissions except system-critical ones
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view products',
            'view orders',
            'edit orders',
            'cancel orders',
            'refund orders',
            'view users',
            'edit users',
            'ban users',
            'view vendors',
            'approve vendors',
            'suspend vendors',
            'edit vendors',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view reviews',
            'moderate reviews',
            'delete reviews',
            'view reports',
            'view analytics',
            'view coupons',
            'create coupons',
            'edit coupons',
            'delete coupons',
        ]);

        // Vendor - product and order management for their own items
        $vendor = Role::create(['name' => 'vendor']);
        $vendor->givePermissionTo([
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view orders',
            'edit orders',
            'view reviews',
            'view reports',
            'view coupons',
            'create coupons',
            'edit coupons',
            'delete coupons',
        ]);

        // Customer - basic shopping permissions
        $customer = Role::create(['name' => 'customer']);
        $customer->givePermissionTo([
            'view products',
            'create orders',
            'view orders',
            'view reviews',
        ]);
    }
}
