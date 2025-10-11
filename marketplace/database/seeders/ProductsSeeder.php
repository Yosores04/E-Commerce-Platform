<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = Vendor::all();
        $categories = Category::whereNotNull('parent_id')->get(); // Only subcategories

        // Electronics products
        $electronicsProducts = [
            [
                'name' => 'iPhone 15 Pro Max',
                'category' => 'Smartphones',
                'description' => 'Latest Apple smartphone with A17 Pro chip, titanium design, and advanced camera system',
                'price' => 1199.99,
                'compare_at_price' => 1299.99,
                'cost' => 950.00,
                'quantity' => 45,
                'sku_prefix' => 'IPH15PM',
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'category' => 'Smartphones',
                'description' => 'Premium Android phone with S Pen, 200MP camera, and AI features',
                'price' => 1299.99,
                'compare_at_price' => 1399.99,
                'cost' => 1050.00,
                'quantity' => 38,
                'sku_prefix' => 'SGS24U',
            ],
            [
                'name' => 'MacBook Pro 16" M3',
                'category' => 'Laptops',
                'description' => 'Professional laptop with M3 chip, 16GB RAM, 512GB SSD',
                'price' => 2499.99,
                'compare_at_price' => 2699.99,
                'cost' => 2000.00,
                'quantity' => 22,
                'sku_prefix' => 'MBP16M3',
            ],
            [
                'name' => 'Dell XPS 15',
                'category' => 'Laptops',
                'description' => 'High-performance laptop with Intel i7, 16GB RAM, NVIDIA RTX 4050',
                'price' => 1899.99,
                'compare_at_price' => 2099.99,
                'cost' => 1500.00,
                'quantity' => 18,
                'sku_prefix' => 'DXPS15',
            ],
            [
                'name' => 'iPad Pro 12.9"',
                'category' => 'Tablets',
                'description' => 'Premium tablet with M2 chip, Liquid Retina display, Apple Pencil support',
                'price' => 1099.99,
                'compare_at_price' => 1199.99,
                'cost' => 850.00,
                'quantity' => 30,
                'sku_prefix' => 'IPADP12',
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'category' => 'Headphones',
                'description' => 'Premium noise-canceling headphones with 30-hour battery life',
                'price' => 399.99,
                'compare_at_price' => 449.99,
                'cost' => 280.00,
                'quantity' => 65,
                'sku_prefix' => 'SWXM5',
            ],
            [
                'name' => 'Canon EOS R6 Mark II',
                'category' => 'Cameras',
                'description' => 'Full-frame mirrorless camera with 24MP sensor and 4K video',
                'price' => 2499.99,
                'compare_at_price' => 2699.99,
                'cost' => 1900.00,
                'quantity' => 12,
                'sku_prefix' => 'CEOSR6M2',
            ],
            [
                'name' => 'Apple Watch Series 9',
                'category' => 'Smart Watches',
                'description' => 'Advanced smartwatch with health tracking and always-on display',
                'price' => 429.99,
                'compare_at_price' => 479.99,
                'cost' => 320.00,
                'quantity' => 55,
                'sku_prefix' => 'AWS9',
            ],
        ];

        // Fashion products
        $fashionProducts = [
            [
                'name' => 'Men\'s Leather Jacket',
                'category' => 'Men\'s Clothing',
                'description' => 'Genuine leather jacket with modern fit and quality stitching',
                'price' => 249.99,
                'compare_at_price' => 299.99,
                'cost' => 150.00,
                'quantity' => 40,
                'sku_prefix' => 'MLJ',
            ],
            [
                'name' => 'Women\'s Summer Dress',
                'category' => 'Women\'s Clothing',
                'description' => 'Elegant floral print dress perfect for summer occasions',
                'price' => 79.99,
                'compare_at_price' => 99.99,
                'cost' => 45.00,
                'quantity' => 85,
                'sku_prefix' => 'WSD',
            ],
            [
                'name' => 'Nike Air Max 270',
                'category' => 'Shoes',
                'description' => 'Comfortable running shoes with Air cushioning technology',
                'price' => 159.99,
                'compare_at_price' => 179.99,
                'cost' => 95.00,
                'quantity' => 120,
                'sku_prefix' => 'NAM270',
            ],
            [
                'name' => 'Designer Handbag',
                'category' => 'Bags & Accessories',
                'description' => 'Luxury leather handbag with gold hardware and adjustable strap',
                'price' => 389.99,
                'compare_at_price' => 449.99,
                'cost' => 220.00,
                'quantity' => 28,
                'sku_prefix' => 'DHB',
            ],
            [
                'name' => 'Gold Chain Necklace',
                'category' => 'Jewelry',
                'description' => '18K gold plated chain necklace with elegant design',
                'price' => 129.99,
                'compare_at_price' => 159.99,
                'cost' => 75.00,
                'quantity' => 45,
                'sku_prefix' => 'GCN',
            ],
        ];

        // Home & Living products
        $homeProducts = [
            [
                'name' => 'Modern Sofa Set',
                'category' => 'Furniture',
                'description' => '3-seater sofa with premium fabric and solid wood frame',
                'price' => 899.99,
                'compare_at_price' => 1099.99,
                'cost' => 550.00,
                'quantity' => 15,
                'sku_prefix' => 'MSS3',
            ],
            [
                'name' => 'Stainless Steel Cookware Set',
                'category' => 'Kitchen & Dining',
                'description' => '10-piece professional cookware set with non-stick coating',
                'price' => 199.99,
                'compare_at_price' => 249.99,
                'cost' => 120.00,
                'quantity' => 42,
                'sku_prefix' => 'SSCS10',
            ],
            [
                'name' => 'Egyptian Cotton Sheets',
                'category' => 'Bedding',
                'description' => 'Luxury 800 thread count sheet set, queen size',
                'price' => 149.99,
                'compare_at_price' => 189.99,
                'cost' => 85.00,
                'quantity' => 68,
                'sku_prefix' => 'ECSQ',
            ],
            [
                'name' => 'Canvas Wall Art Set',
                'category' => 'Home Decor',
                'description' => 'Modern abstract art, set of 3 canvas prints',
                'price' => 79.99,
                'compare_at_price' => 99.99,
                'cost' => 40.00,
                'quantity' => 55,
                'sku_prefix' => 'CWAS3',
            ],
        ];

        // Beauty & Health products
        $beautyProducts = [
            [
                'name' => 'Anti-Aging Serum',
                'category' => 'Skincare',
                'description' => 'Advanced formula with hyaluronic acid and vitamin C',
                'price' => 89.99,
                'compare_at_price' => 109.99,
                'cost' => 45.00,
                'quantity' => 95,
                'sku_prefix' => 'AAS',
            ],
            [
                'name' => 'Professional Makeup Kit',
                'category' => 'Makeup',
                'description' => 'Complete makeup set with brushes and high-quality cosmetics',
                'price' => 149.99,
                'compare_at_price' => 199.99,
                'cost' => 80.00,
                'quantity' => 38,
                'sku_prefix' => 'PMK',
            ],
            [
                'name' => 'Hair Styling Tool Set',
                'category' => 'Hair Care',
                'description' => 'Professional hair dryer and straightener set',
                'price' => 129.99,
                'compare_at_price' => 159.99,
                'cost' => 70.00,
                'quantity' => 45,
                'sku_prefix' => 'HSTS',
            ],
            [
                'name' => 'Designer Perfume',
                'category' => 'Fragrances',
                'description' => 'Luxury eau de parfum, 100ml bottle',
                'price' => 119.99,
                'compare_at_price' => 149.99,
                'cost' => 65.00,
                'quantity' => 72,
                'sku_prefix' => 'DP100',
            ],
            [
                'name' => 'Yoga Mat Premium',
                'category' => 'Fitness Equipment',
                'description' => 'Non-slip yoga mat with carrying strap, 6mm thick',
                'price' => 49.99,
                'compare_at_price' => 69.99,
                'cost' => 25.00,
                'quantity' => 110,
                'sku_prefix' => 'YMP6',
            ],
        ];

        // Sports & Outdoors products
        $sportsProducts = [
            [
                'name' => 'Adjustable Dumbbell Set',
                'category' => 'Exercise & Fitness',
                'description' => 'Quick-adjust dumbbells, 5-52.5 lbs per hand',
                'price' => 349.99,
                'compare_at_price' => 399.99,
                'cost' => 200.00,
                'quantity' => 28,
                'sku_prefix' => 'ADS52',
            ],
            [
                'name' => 'Camping Tent 4-Person',
                'category' => 'Camping & Hiking',
                'description' => 'Waterproof tent with easy setup, includes rain fly',
                'price' => 199.99,
                'compare_at_price' => 249.99,
                'cost' => 110.00,
                'quantity' => 35,
                'sku_prefix' => 'CT4P',
            ],
            [
                'name' => 'Mountain Bike 27.5"',
                'category' => 'Cycling',
                'description' => '21-speed mountain bike with aluminum frame',
                'price' => 499.99,
                'compare_at_price' => 599.99,
                'cost' => 300.00,
                'quantity' => 18,
                'sku_prefix' => 'MB275',
            ],
            [
                'name' => 'Basketball Official Size',
                'category' => 'Team Sports',
                'description' => 'Official size 7 basketball with premium grip',
                'price' => 29.99,
                'compare_at_price' => 39.99,
                'cost' => 15.00,
                'quantity' => 145,
                'sku_prefix' => 'BOS7',
            ],
        ];

        // Books & Media products
        $booksProducts = [
            [
                'name' => 'The Success Mindset',
                'category' => 'Books',
                'description' => 'Bestselling self-help book about achieving your goals',
                'price' => 24.99,
                'compare_at_price' => 29.99,
                'cost' => 12.00,
                'quantity' => 88,
                'sku_prefix' => 'TSM',
            ],
            [
                'name' => 'Wireless Headphones Gaming',
                'category' => 'Video Games',
                'description' => '7.1 surround sound gaming headset with mic',
                'price' => 89.99,
                'compare_at_price' => 119.99,
                'cost' => 50.00,
                'quantity' => 62,
                'sku_prefix' => 'WHG71',
            ],
        ];

        // Toys & Kids products
        $toysProducts = [
            [
                'name' => 'LEGO Creator Set',
                'category' => 'Toys',
                'description' => '1000-piece building set for ages 8+',
                'price' => 79.99,
                'compare_at_price' => 99.99,
                'cost' => 45.00,
                'quantity' => 75,
                'sku_prefix' => 'LCS1000',
            ],
            [
                'name' => 'Baby Stroller Premium',
                'category' => 'Baby Products',
                'description' => 'Lightweight stroller with adjustable seat and sun canopy',
                'price' => 299.99,
                'compare_at_price' => 349.99,
                'cost' => 180.00,
                'quantity' => 22,
                'sku_prefix' => 'BSP',
            ],
            [
                'name' => 'Kids Sneakers Light-Up',
                'category' => 'Kids Clothing',
                'description' => 'LED light-up shoes, sizes 10-3, multiple colors',
                'price' => 44.99,
                'compare_at_price' => 54.99,
                'cost' => 22.00,
                'quantity' => 95,
                'sku_prefix' => 'KSLU',
            ],
            [
                'name' => 'Educational Science Kit',
                'category' => 'Educational',
                'description' => 'STEM learning kit with 50+ experiments',
                'price' => 59.99,
                'compare_at_price' => 79.99,
                'cost' => 30.00,
                'quantity' => 48,
                'sku_prefix' => 'ESK50',
            ],
        ];

        // Automotive products
        $autoProducts = [
            [
                'name' => 'All-Season Tires Set',
                'category' => 'Tires & Wheels',
                'description' => 'Set of 4 premium all-season tires, 225/65R17',
                'price' => 599.99,
                'compare_at_price' => 699.99,
                'cost' => 380.00,
                'quantity' => 24,
                'sku_prefix' => 'AST4',
            ],
            [
                'name' => 'Car Dash Cam',
                'category' => 'Car Accessories',
                'description' => '4K dash camera with night vision and GPS',
                'price' => 149.99,
                'compare_at_price' => 189.99,
                'cost' => 85.00,
                'quantity' => 56,
                'sku_prefix' => 'CDC4K',
            ],
            [
                'name' => 'Mechanic Tool Set',
                'category' => 'Tools & Equipment',
                'description' => '150-piece professional tool set with carrying case',
                'price' => 249.99,
                'compare_at_price' => 299.99,
                'cost' => 140.00,
                'quantity' => 32,
                'sku_prefix' => 'MTS150',
            ],
        ];

        // Combine all products
        $allProducts = array_merge(
            $electronicsProducts,
            $fashionProducts,
            $homeProducts,
            $beautyProducts,
            $sportsProducts,
            $booksProducts,
            $toysProducts,
            $autoProducts
        );

        // Create products
        foreach ($allProducts as $productData) {
            // Find the category
            $category = $categories->firstWhere('name', $productData['category']);
            
            if (!$category) {
                continue; // Skip if category not found
            }

            // Randomly assign to a vendor
            $vendor = $vendors->random();

            // Determine if featured (20% chance)
            $isFeatured = rand(1, 100) <= 20;
            $isActive = true;

            Product::create([
                'vendor_id' => $vendor->id,
                'category_id' => $category->id,
                'name' => $productData['name'],
                'slug' => \Illuminate\Support\Str::slug($productData['name']),
                'description' => $productData['description'],
                'short_description' => substr($productData['description'], 0, 100),
                'sku' => $productData['sku_prefix'] . '-' . strtoupper(substr(md5(uniqid()), 0, 6)),
                'price' => $productData['price'],
                'compare_at_price' => $productData['compare_at_price'],
                'cost' => $productData['cost'],
                'quantity' => $productData['quantity'],
                'low_stock_threshold' => 10,
                'weight' => rand(100, 5000) / 100, // Random weight between 1-50 kg
                'length' => rand(10, 100),
                'width' => rand(10, 100),
                'height' => rand(10, 100),
                'status' => $isActive ? 'active' : 'inactive',
                'is_featured' => $isFeatured,
                'meta_title' => $productData['name'],
                'meta_description' => $productData['description'],
            ]);
        }

        $this->command->info('Products seeded successfully!');
        $this->command->info('Total products created: ' . count($allProducts));
    }
}
