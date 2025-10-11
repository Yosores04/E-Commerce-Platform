<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@marketplace.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@marketplace.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567891',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super-admin');

        // Vendor Users (10 vendors)
        $vendorNames = [
            'Tech Store',
            'Fashion Hub',
            'Home Essentials',
            'Beauty Corner',
            'Sports World',
            'Book Haven',
            'Kids Paradise',
            'Auto Parts Pro',
            'Electronics Plus',
            'Style Avenue',
        ];

        foreach ($vendorNames as $index => $name) {
            $user = User::create([
                'name' => $name . ' Owner',
                'email' => strtolower(str_replace(' ', '', $name)) . '@vendor.com',
                'password' => Hash::make('password'),
                'phone' => '+123456' . str_pad($index + 100, 4, '0', STR_PAD_LEFT),
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $user->assignRole('vendor');
        }

        // Customer Users (20 customers)
        $customerNames = [
            'John Doe',
            'Jane Smith',
            'Michael Brown',
            'Emily Davis',
            'David Wilson',
            'Sarah Martinez',
            'Chris Anderson',
            'Jessica Taylor',
            'Daniel Thomas',
            'Ashley Jackson',
            'Matthew White',
            'Amanda Harris',
            'James Martin',
            'Lisa Thompson',
            'Robert Garcia',
            'Jennifer Rodriguez',
            'William Lee',
            'Mary Walker',
            'Richard Hall',
            'Patricia Allen',
        ];

        foreach ($customerNames as $index => $name) {
            $user = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@customer.com',
                'password' => Hash::make('password'),
                'phone' => '+123456' . str_pad($index + 200, 4, '0', STR_PAD_LEFT),
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $user->assignRole('customer');
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@marketplace.com / password');
        $this->command->info('Super Admin: superadmin@marketplace.com / password');
        $this->command->info('Vendors: <vendorname>@vendor.com / password');
        $this->command->info('Customers: <firstname>.<lastname>@customer.com / password');
    }
}

