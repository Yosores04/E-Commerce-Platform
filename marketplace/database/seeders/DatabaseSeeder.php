<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            VendorsSeeder::class,
            CategoriesSeeder::class,
            ProductsSeeder::class,
            AddressesSeeder::class,
        ]);
    }
}
