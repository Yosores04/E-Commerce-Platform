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
                'shop_name' => 'Tech Store',
                'shop_description' => 'Your one-stop shop for the latest electronics and gadgets',
                'business_name' => 'Tech Store LLC',
                'business_email' => 'contact@techstore.com',
                'business_phone' => '+1234567100',
                'address_line1' => '123 Tech Street',
                'city' => 'San Francisco',
                'state' => 'California',
                'country' => 'USA',
                'postal_code' => '94102',
                'commission_rate' => 15.00,
            ],
            [
                'shop_name' => 'Fashion Hub',
                'shop_description' => 'Trendy fashion for everyone',
                'business_name' => 'Fashion Hub Inc',
                'business_email' => 'hello@fashionhub.com',
                'business_phone' => '+1234567101',
                'address_line1' => '456 Style Avenue',
                'city' => 'New York',
                'state' => 'New York',
                'country' => 'USA',
                'postal_code' => '10001',
                'commission_rate' => 20.00,
            ],
            [
                'shop_name' => 'Home Essentials',
                'shop_description' => 'Quality products for your home',
                'business_name' => 'Home Essentials Co',
                'business_email' => 'info@homeessentials.com',
                'business_phone' => '+1234567102',
                'address_line1' => '789 Home Lane',
                'city' => 'Los Angeles',
                'state' => 'California',
                'country' => 'USA',
                'postal_code' => '90001',
                'commission_rate' => 18.00,
            ],
            [
                'shop_name' => 'Beauty Corner',
                'shop_description' => 'Premium beauty and health products',
                'business_name' => 'Beauty Corner LLC',
                'business_email' => 'support@beautycorner.com',
                'business_phone' => '+1234567103',
                'address_line1' => '321 Beauty Blvd',
                'city' => 'Miami',
                'state' => 'Florida',
                'country' => 'USA',
                'postal_code' => '33101',
                'commission_rate' => 25.00,
            ],
            [
                'shop_name' => 'Sports World',
                'shop_description' => 'Everything for sports and fitness',
                'business_name' => 'Sports World Inc',
                'business_email' => 'contact@sportsworld.com',
                'business_phone' => '+1234567104',
                'address_line1' => '654 Sports Drive',
                'city' => 'Chicago',
                'state' => 'Illinois',
                'country' => 'USA',
                'postal_code' => '60601',
                'commission_rate' => 15.00,
            ],
            [
                'shop_name' => 'Book Haven',
                'shop_description' => 'Books for every reader',
                'business_name' => 'Book Haven Co',
                'business_email' => 'hello@bookhaven.com',
                'business_phone' => '+1234567105',
                'address_line1' => '987 Library Street',
                'city' => 'Boston',
                'state' => 'Massachusetts',
                'country' => 'USA',
                'postal_code' => '02101',
                'commission_rate' => 12.00,
            ],
            [
                'shop_name' => 'Kids Paradise',
                'shop_description' => 'Toys and products for happy kids',
                'business_name' => 'Kids Paradise LLC',
                'business_email' => 'info@kidsparadise.com',
                'business_phone' => '+1234567106',
                'address_line1' => '246 Playground Road',
                'city' => 'Seattle',
                'state' => 'Washington',
                'country' => 'USA',
                'postal_code' => '98101',
                'commission_rate' => 20.00,
            ],
            [
                'shop_name' => 'Auto Parts Pro',
                'shop_description' => 'Quality automotive parts and accessories',
                'business_name' => 'Auto Parts Pro Inc',
                'business_email' => 'support@autopartspro.com',
                'business_phone' => '+1234567107',
                'address_line1' => '135 Motor Avenue',
                'city' => 'Detroit',
                'state' => 'Michigan',
                'country' => 'USA',
                'postal_code' => '48201',
                'commission_rate' => 15.00,
            ],
            [
                'shop_name' => 'Electronics Plus',
                'shop_description' => 'More electronics, more choices',
                'business_name' => 'Electronics Plus Co',
                'business_email' => 'contact@electronicsplus.com',
                'business_phone' => '+1234567108',
                'address_line1' => '579 Circuit Boulevard',
                'city' => 'Austin',
                'state' => 'Texas',
                'country' => 'USA',
                'postal_code' => '73301',
                'commission_rate' => 15.00,
            ],
            [
                'shop_name' => 'Style Avenue',
                'shop_description' => 'Fashion-forward clothing and accessories',
                'business_name' => 'Style Avenue LLC',
                'business_email' => 'hello@styleavenue.com',
                'business_phone' => '+1234567109',
                'address_line1' => '864 Fashion Street',
                'city' => 'Portland',
                'state' => 'Oregon',
                'country' => 'USA',
                'postal_code' => '97201',
                'commission_rate' => 20.00,
            ],
        ];

        foreach ($vendors as $vendorData) {
            // Find the corresponding user
            $shopName = $vendorData['shop_name'];
            $user = User::whereHas('roles', function($query) {
                $query->where('name', 'vendor');
            })->where('name', $shopName . ' Owner')->first();

            if ($user) {
                $vendorData['user_id'] = $user->id;
                $vendorData['shop_slug'] = Str::slug($shopName);
                $vendorData['status'] = 'approved';
                $vendorData['approval_date'] = now();

                Vendor::create($vendorData);
            }
        }

        $this->command->info('Vendors seeded successfully!');
    }
}

