<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all customer users
        $customers = User::role('customer')->get();

        $addressTemplates = [
            [
                'type' => 'shipping',
                'addresses' => [
                    ['street' => '123 Main Street', 'city' => 'New York', 'state' => 'NY', 'postal' => '10001'],
                    ['street' => '456 Oak Avenue', 'city' => 'Los Angeles', 'state' => 'CA', 'postal' => '90001'],
                    ['street' => '789 Pine Road', 'city' => 'Chicago', 'state' => 'IL', 'postal' => '60601'],
                    ['street' => '321 Elm Street', 'city' => 'Houston', 'state' => 'TX', 'postal' => '77001'],
                    ['street' => '654 Maple Drive', 'city' => 'Phoenix', 'state' => 'AZ', 'postal' => '85001'],
                    ['street' => '987 Cedar Lane', 'city' => 'Philadelphia', 'state' => 'PA', 'postal' => '19101'],
                    ['street' => '147 Birch Court', 'city' => 'San Antonio', 'state' => 'TX', 'postal' => '78201'],
                    ['street' => '258 Spruce Way', 'city' => 'San Diego', 'state' => 'CA', 'postal' => '92101'],
                    ['street' => '369 Willow Place', 'city' => 'Dallas', 'state' => 'TX', 'postal' => '75201'],
                    ['street' => '741 Ash Boulevard', 'city' => 'San Jose', 'state' => 'CA', 'postal' => '95101'],
                    ['street' => '852 Cherry Avenue', 'city' => 'Austin', 'state' => 'TX', 'postal' => '73301'],
                    ['street' => '963 Hickory Street', 'city' => 'Jacksonville', 'state' => 'FL', 'postal' => '32099'],
                    ['street' => '159 Walnut Road', 'city' => 'Fort Worth', 'state' => 'TX', 'postal' => '76101'],
                    ['street' => '357 Poplar Drive', 'city' => 'Columbus', 'state' => 'OH', 'postal' => '43004'],
                    ['street' => '486 Sycamore Lane', 'city' => 'San Francisco', 'state' => 'CA', 'postal' => '94102'],
                    ['street' => '753 Magnolia Court', 'city' => 'Charlotte', 'state' => 'NC', 'postal' => '28201'],
                    ['street' => '951 Dogwood Way', 'city' => 'Indianapolis', 'state' => 'IN', 'postal' => '46201'],
                    ['street' => '842 Redwood Place', 'city' => 'Seattle', 'state' => 'WA', 'postal' => '98101'],
                    ['street' => '624 Beech Boulevard', 'city' => 'Denver', 'state' => 'CO', 'postal' => '80201'],
                    ['street' => '135 Cypress Avenue', 'city' => 'Boston', 'state' => 'MA', 'postal' => '02101'],
                ],
            ],
            [
                'type' => 'billing',
                'addresses' => [
                    ['street' => '100 Business Plaza', 'city' => 'New York', 'state' => 'NY', 'postal' => '10002'],
                    ['street' => '200 Corporate Center', 'city' => 'Los Angeles', 'state' => 'CA', 'postal' => '90002'],
                    ['street' => '300 Office Park', 'city' => 'Chicago', 'state' => 'IL', 'postal' => '60602'],
                    ['street' => '400 Commerce Street', 'city' => 'Houston', 'state' => 'TX', 'postal' => '77002'],
                    ['street' => '500 Trade Avenue', 'city' => 'Phoenix', 'state' => 'AZ', 'postal' => '85002'],
                    ['street' => '600 Industry Road', 'city' => 'Philadelphia', 'state' => 'PA', 'postal' => '19102'],
                    ['street' => '700 Enterprise Drive', 'city' => 'San Antonio', 'state' => 'TX', 'postal' => '78202'],
                    ['street' => '800 Innovation Way', 'city' => 'San Diego', 'state' => 'CA', 'postal' => '92102'],
                    ['street' => '900 Technology Lane', 'city' => 'Dallas', 'state' => 'TX', 'postal' => '75202'],
                    ['street' => '1000 Silicon Boulevard', 'city' => 'San Jose', 'state' => 'CA', 'postal' => '95102'],
                    ['street' => '1100 Startup Street', 'city' => 'Austin', 'state' => 'TX', 'postal' => '73302'],
                    ['street' => '1200 Finance Plaza', 'city' => 'Jacksonville', 'state' => 'FL', 'postal' => '32098'],
                    ['street' => '1300 Banking Center', 'city' => 'Fort Worth', 'state' => 'TX', 'postal' => '76102'],
                    ['street' => '1400 Medical District', 'city' => 'Columbus', 'state' => 'OH', 'postal' => '43005'],
                    ['street' => '1500 Market Street', 'city' => 'San Francisco', 'state' => 'CA', 'postal' => '94103'],
                    ['street' => '1600 Research Park', 'city' => 'Charlotte', 'state' => 'NC', 'postal' => '28202'],
                    ['street' => '1700 Science Drive', 'city' => 'Indianapolis', 'state' => 'IN', 'postal' => '46202'],
                    ['street' => '1800 Tech Campus', 'city' => 'Seattle', 'state' => 'WA', 'postal' => '98102'],
                    ['street' => '1900 Energy Plaza', 'city' => 'Denver', 'state' => 'CO', 'postal' => '80202'],
                    ['street' => '2000 Innovation Hub', 'city' => 'Boston', 'state' => 'MA', 'postal' => '02102'],
                ],
            ],
            [
                'type' => 'shipping',
                'addresses' => [
                    ['street' => '555 Delivery Lane', 'city' => 'Miami', 'state' => 'FL', 'postal' => '33101'],
                    ['street' => '666 Package Court', 'city' => 'Atlanta', 'state' => 'GA', 'postal' => '30301'],
                    ['street' => '777 Logistics Road', 'city' => 'Portland', 'state' => 'OR', 'postal' => '97201'],
                    ['street' => '888 Warehouse Way', 'city' => 'Nashville', 'state' => 'TN', 'postal' => '37201'],
                    ['street' => '999 Distribution Drive', 'city' => 'Detroit', 'state' => 'MI', 'postal' => '48201'],
                    ['street' => '1111 Freight Avenue', 'city' => 'Memphis', 'state' => 'TN', 'postal' => '38101'],
                    ['street' => '2222 Cargo Street', 'city' => 'Baltimore', 'state' => 'MD', 'postal' => '21201'],
                    ['street' => '3333 Transport Boulevard', 'city' => 'Milwaukee', 'state' => 'WI', 'postal' => '53201'],
                    ['street' => '4444 Shipping Plaza', 'city' => 'Albuquerque', 'state' => 'NM', 'postal' => '87101'],
                    ['street' => '5555 Route Center', 'city' => 'Tucson', 'state' => 'AZ', 'postal' => '85701'],
                    ['street' => '6666 Express Lane', 'city' => 'Fresno', 'state' => 'CA', 'postal' => '93701'],
                    ['street' => '7777 Transit Way', 'city' => 'Sacramento', 'state' => 'CA', 'postal' => '94201'],
                    ['street' => '8888 Dispatch Road', 'city' => 'Kansas City', 'state' => 'MO', 'postal' => '64101'],
                    ['street' => '9999 Carrier Street', 'city' => 'Mesa', 'state' => 'AZ', 'postal' => '85201'],
                    ['street' => '1010 Delivery Hub', 'city' => 'Virginia Beach', 'state' => 'VA', 'postal' => '23450'],
                    ['street' => '2020 Package Center', 'city' => 'Omaha', 'state' => 'NE', 'postal' => '68101'],
                    ['street' => '3030 Post Office Box', 'city' => 'Oakland', 'state' => 'CA', 'postal' => '94601'],
                    ['street' => '4040 Mail Route', 'city' => 'Minneapolis', 'state' => 'MN', 'postal' => '55401'],
                    ['street' => '5050 Parcel Way', 'city' => 'Tulsa', 'state' => 'OK', 'postal' => '74101'],
                    ['street' => '6060 Fulfillment Drive', 'city' => 'Arlington', 'state' => 'TX', 'postal' => '76010'],
                ],
            ],
        ];

        $addressIndex = 0;
        
        foreach ($customers as $index => $customer) {
            // Each customer gets 2-3 addresses
            $numAddresses = rand(2, 3);
            
            for ($i = 0; $i < $numAddresses; $i++) {
                $typeIndex = $i % 3; // Cycle through home, work, shipping
                $addressType = $addressTemplates[$typeIndex]['type'];
                $addressData = $addressTemplates[$typeIndex]['addresses'][$addressIndex % 20];
                
                Address::create([
                    'user_id' => $customer->id,
                    'type' => $addressType,
                    'first_name' => explode(' ', $customer->name)[0],
                    'last_name' => explode(' ', $customer->name)[1] ?? 'Customer',
                    'phone' => $customer->phone,
                    'address_line_1' => $addressData['street'],
                    'address_line_2' => $i == 1 ? 'Apt ' . rand(1, 99) : null, // Only work addresses have apartments
                    'city' => $addressData['city'],
                    'state' => $addressData['state'],
                    'country' => 'US',
                    'postal_code' => $addressData['postal'],
                    'is_default' => $i === 0, // First address is default
                ]);
                
                $addressIndex++;
            }
        }

        $this->command->info('Addresses seeded successfully!');
        $this->command->info('Total addresses created: ' . ($customers->count() * 2.5)); // Average 2.5 addresses per customer
    }
}
