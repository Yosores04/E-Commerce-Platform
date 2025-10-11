<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Support\Str;

class VendorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'business_name' => 'Tech Store',
                'description' => 'Your one-stop shop for the latest electronics and gadgets',
                'business_email' => 'contact@techstore.com',
                'business_phone' => '+1234567100',
                'business_address' => '123 Tech Street',
                'business_city' => 'San Francisco',
                'business_state' => 'California',
                'business_country' => 'USA',
                'business_postal_code' => '94102',
                'commission_rate' => 15.00,
            ],
            [
                'business_name' => 'Fashion Hub',
                'description' => 'Trendy fashion for everyone',
                'business_email' => 'hello@fashionhub.com',
                'business_phone' => '+1234567101',
                'business_address' => '456 Style Avenue',
                'business_city' => 'New York',
                'business_state' => 'New York',
                'business_country' => 'USA',
                'business_postal_code' => '10001',
                'commission_rate' => 20.00,
            ],
            [
                'business_name' => 'Home Essentials',
                'description' => 'Quality products for your home',
                'business_email' => 'info@homeessentials.com',
                'business_phone' => '+1234567102',
                'business_address' => '789 Home Lane',
                'business_city' => 'Los Angeles',
                'business_state' => 'California',
                'business_country' => 'USA',
                'business_postal_code' => '90001',
                'commission_rate' => 18.00,
            ],
            [
                'business_name' => 'Beauty Corner',
                'description' => 'Premium beauty and health products',
                'business_email' => 'support@beautycorner.com',
                'business_phone' => '+1234567103',
                'business_address' => '321 Beauty Blvd',
                'business_city' => 'Miami',
                'business_state' => 'Florida',
                'business_country' => 'USA',
                'business_postal_code' => '33101',
                'commission_rate' => 25.00,
            ],
            [
                'business_name' => 'Sports World',
                'description' => 'Everything for sports and fitness',
                'business_email' => 'contact@sportsworld.com',
                'business_phone' => '+1234567104',
                'business_address' => '654 Sports Drive',
                'business_city' => 'Chicago',
                'business_state' => 'Illinois',
                'business_country' => 'USA',
                'business_postal_code' => '60601',
                'commission_rate' => 15.00,
            ],
            [
                'business_name' => 'Book Haven',
                'description' => 'Books for every reader',
                'business_email' => 'hello@bookhaven.com',
                'business_phone' => '+1234567105',
                'business_address' => '987 Library Street',
                'business_city' => 'Boston',
                'business_state' => 'Massachusetts',
                'business_country' => 'USA',
                'business_postal_code' => '02101',
                'commission_rate' => 12.00,
            ],
            [
                'business_name' => 'Kids Paradise',
                'description' => 'Toys and products for happy kids',
                'business_email' => 'info@kidsparadise.com',
                'business_phone' => '+1234567106',
                'business_address' => '246 Playground Road',
                'business_city' => 'Seattle',
                'business_state' => 'Washington',
                'business_country' => 'USA',
                'business_postal_code' => '98101',
                'commission_rate' => 20.00,
            ],
            [
                'business_name' => 'Auto Parts Pro',
                'description' => 'Quality automotive parts and accessories',
                'business_email' => 'support@autopartspro.com',
                'business_phone' => '+1234567107',
                'business_address' => '135 Motor Avenue',
                'business_city' => 'Detroit',
                'business_state' => 'Michigan',
                'business_country' => 'USA',
                'business_postal_code' => '48201',
                'commission_rate' => 15.00,
            ],
            [
                'business_name' => 'Electronics Plus',
                'description' => 'More electronics, more choices',
                'business_email' => 'contact@electronicsplus.com',
                'business_phone' => '+1234567108',
                'business_address' => '579 Circuit Boulevard',
                'business_city' => 'Austin',
                'business_state' => 'Texas',
                'business_country' => 'USA',
                'business_postal_code' => '73301',
                'commission_rate' => 15.00,
            ],
            [
                'business_name' => 'Style Avenue',
                'description' => 'Fashion-forward clothing and accessories',
                'business_email' => 'hello@styleavenue.com',
                'business_phone' => '+1234567109',
                'business_address' => '864 Fashion Street',
                'business_city' => 'Portland',
                'business_state' => 'Oregon',
                'business_country' => 'USA',
                'business_postal_code' => '97201',
                'commission_rate' => 20.00,
            ],
        ];

        foreach ($vendors as $vendorData) {
            // Find the corresponding user
            $businessName = $vendorData['business_name'];
            $user = User::whereHas('roles', function($query) {
                $query->where('name', 'vendor');
            })->where('name', $businessName . ' Owner')->first();

            if ($user) {
                $vendorData['user_id'] = $user->id;
                $vendorData['slug'] = Str::slug($businessName);
                $vendorData['status'] = 'approved';
                $vendorData['approved_at'] = now();

                Vendor::create($vendorData);
            }
        }

        $this->command->info('Vendors seeded successfully!');
    }
}

