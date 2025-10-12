<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
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

        // Electronics products (Prices in Philippine Peso - ₱)
        $electronicsProducts = [
            [
                'name' => 'iPhone 15 Pro Max',
                'category' => 'Smartphones',
                'description' => 'Latest Apple smartphone with A17 Pro chip, titanium design, and advanced camera system',
                'price' => 68399.00,
                'compare_at_price' => 74099.00,
                'cost' => 54150.00,
                'quantity' => 45,
                'sku_prefix' => 'IPH15PM',
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'category' => 'Smartphones',
                'description' => 'Premium Android phone with S Pen, 200MP camera, and AI features',
                'price' => 74099.00,
                'compare_at_price' => 79799.00,
                'cost' => 59850.00,
                'quantity' => 38,
                'sku_prefix' => 'SGS24U',
            ],
            [
                'name' => 'MacBook Pro 16" M3',
                'category' => 'Laptops',
                'description' => 'Professional laptop with M3 chip, 16GB RAM, 512GB SSD',
                'price' => 142499.00,
                'compare_at_price' => 153899.00,
                'cost' => 114000.00,
                'quantity' => 22,
                'sku_prefix' => 'MBP16M3',
            ],
            [
                'name' => 'Dell XPS 15',
                'category' => 'Laptops',
                'description' => 'High-performance laptop with Intel i7, 16GB RAM, NVIDIA RTX 4050',
                'price' => 108299.00,
                'compare_at_price' => 119699.00,
                'cost' => 85500.00,
                'quantity' => 18,
                'sku_prefix' => 'DXPS15',
            ],
            [
                'name' => 'iPad Pro 12.9"',
                'category' => 'Tablets',
                'description' => 'Premium tablet with M2 chip, Liquid Retina display, Apple Pencil support',
                'price' => 62699.00,
                'compare_at_price' => 68399.00,
                'cost' => 48450.00,
                'quantity' => 30,
                'sku_prefix' => 'IPADP12',
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'category' => 'Headphones',
                'description' => 'Premium noise-canceling headphones with 30-hour battery life',
                'price' => 22799.00,
                'compare_at_price' => 25649.00,
                'cost' => 15960.00,
                'quantity' => 65,
                'sku_prefix' => 'SWXM5',
            ],
            [
                'name' => 'Canon EOS R6 Mark II',
                'category' => 'Cameras',
                'description' => 'Full-frame mirrorless camera with 24MP sensor and 4K video',
                'price' => 142499.00,
                'compare_at_price' => 153899.00,
                'cost' => 108300.00,
                'quantity' => 12,
                'sku_prefix' => 'CEOSR6M2',
            ],
            [
                'name' => 'Apple Watch Series 9',
                'category' => 'Smart Watches',
                'description' => 'Advanced smartwatch with health tracking and always-on display',
                'price' => 24509.00,
                'compare_at_price' => 27359.00,
                'cost' => 18240.00,
                'quantity' => 55,
                'sku_prefix' => 'AWS9',
            ],
        ];

        // Fashion products (Prices in Philippine Peso - ₱)
        $fashionProducts = [
            [
                'name' => 'Men\'s Leather Jacket',
                'category' => 'Men\'s Clothing',
                'description' => 'Genuine leather jacket with modern fit and quality stitching',
                'price' => 14249.00,
                'compare_at_price' => 17099.00,
                'cost' => 8550.00,
                'quantity' => 40,
                'sku_prefix' => 'MLJ',
            ],
            [
                'name' => 'Women\'s Summer Dress',
                'category' => 'Women\'s Clothing',
                'description' => 'Elegant floral print dress perfect for summer occasions',
                'price' => 4559.00,
                'compare_at_price' => 5699.00,
                'cost' => 2565.00,
                'quantity' => 85,
                'sku_prefix' => 'WSD',
            ],
            [
                'name' => 'Nike Air Max 270',
                'category' => 'Shoes',
                'description' => 'Comfortable running shoes with Air cushioning technology',
                'price' => 9119.00,
                'compare_at_price' => 10259.00,
                'cost' => 5415.00,
                'quantity' => 120,
                'sku_prefix' => 'NAM270',
            ],
            [
                'name' => 'Designer Handbag',
                'category' => 'Bags & Accessories',
                'description' => 'Luxury leather handbag with gold hardware and adjustable strap',
                'price' => 22229.00,
                'compare_at_price' => 25649.00,
                'cost' => 12540.00,
                'quantity' => 28,
                'sku_prefix' => 'DHB',
            ],
            [
                'name' => 'Gold Chain Necklace',
                'category' => 'Jewelry',
                'description' => '18K gold plated chain necklace with elegant design',
                'price' => 7409.00,
                'compare_at_price' => 9119.00,
                'cost' => 4275.00,
                'quantity' => 45,
                'sku_prefix' => 'GCN',
            ],
        ];

        // Home & Living products (Prices in Philippine Peso - ₱)
        $homeProducts = [
            [
                'name' => 'Modern Sofa Set',
                'category' => 'Furniture',
                'description' => '3-seater sofa with premium fabric and solid wood frame',
                'price' => 51299.00,
                'compare_at_price' => 62699.00,
                'cost' => 31350.00,
                'quantity' => 15,
                'sku_prefix' => 'MSS3',
            ],
            [
                'name' => 'Stainless Steel Cookware Set',
                'category' => 'Kitchen & Dining',
                'description' => '10-piece professional cookware set with non-stick coating',
                'price' => 11399.00,
                'compare_at_price' => 14249.00,
                'cost' => 6840.00,
                'quantity' => 42,
                'sku_prefix' => 'SSCS10',
            ],
            [
                'name' => 'Egyptian Cotton Sheets',
                'category' => 'Bedding',
                'description' => 'Luxury 800 thread count sheet set, queen size',
                'price' => 8549.00,
                'compare_at_price' => 10829.00,
                'cost' => 4845.00,
                'quantity' => 68,
                'sku_prefix' => 'ECSQ',
            ],
            [
                'name' => 'Canvas Wall Art Set',
                'category' => 'Home Decor',
                'description' => 'Modern abstract art, set of 3 canvas prints',
                'price' => 4559.00,
                'compare_at_price' => 5699.00,
                'cost' => 2280.00,
                'quantity' => 55,
                'sku_prefix' => 'CWAS3',
            ],
        ];

        // Beauty & Health products (Prices in Philippine Peso - ₱)
        $beautyProducts = [
            [
                'name' => 'Anti-Aging Serum',
                'category' => 'Skincare',
                'description' => 'Advanced formula with hyaluronic acid and vitamin C',
                'price' => 5129.00,
                'compare_at_price' => 6269.00,
                'cost' => 2565.00,
                'quantity' => 95,
                'sku_prefix' => 'AAS',
            ],
            [
                'name' => 'Professional Makeup Kit',
                'category' => 'Makeup',
                'description' => 'Complete makeup set with brushes and high-quality cosmetics',
                'price' => 8549.00,
                'compare_at_price' => 11399.00,
                'cost' => 4560.00,
                'quantity' => 38,
                'sku_prefix' => 'PMK',
            ],
            [
                'name' => 'Hair Styling Tool Set',
                'category' => 'Hair Care',
                'description' => 'Professional hair dryer and straightener set',
                'price' => 7409.00,
                'compare_at_price' => 9119.00,
                'cost' => 3990.00,
                'quantity' => 45,
                'sku_prefix' => 'HSTS',
            ],
            [
                'name' => 'Designer Perfume',
                'category' => 'Fragrances',
                'description' => 'Luxury eau de parfum, 100ml bottle',
                'price' => 6839.00,
                'compare_at_price' => 8549.00,
                'cost' => 3705.00,
                'quantity' => 72,
                'sku_prefix' => 'DP100',
            ],
            [
                'name' => 'Yoga Mat Premium',
                'category' => 'Fitness Equipment',
                'description' => 'Non-slip yoga mat with carrying strap, 6mm thick',
                'price' => 2849.00,
                'compare_at_price' => 3989.00,
                'cost' => 1425.00,
                'quantity' => 110,
                'sku_prefix' => 'YMP6',
            ],
        ];

        // Sports & Outdoors products (Prices in Philippine Peso - ₱)
        $sportsProducts = [
            [
                'name' => 'Adjustable Dumbbell Set',
                'category' => 'Exercise & Fitness',
                'description' => 'Quick-adjust dumbbells, 5-52.5 lbs per hand',
                'price' => 19949.00,
                'compare_at_price' => 22799.00,
                'cost' => 11400.00,
                'quantity' => 28,
                'sku_prefix' => 'ADS52',
            ],
            [
                'name' => 'Camping Tent 4-Person',
                'category' => 'Camping & Hiking',
                'description' => 'Waterproof tent with easy setup, includes rain fly',
                'price' => 11399.00,
                'compare_at_price' => 14249.00,
                'cost' => 6270.00,
                'quantity' => 35,
                'sku_prefix' => 'CT4P',
            ],
            [
                'name' => 'Mountain Bike 27.5"',
                'category' => 'Cycling',
                'description' => '21-speed mountain bike with aluminum frame',
                'price' => 28499.00,
                'compare_at_price' => 34199.00,
                'cost' => 17100.00,
                'quantity' => 18,
                'sku_prefix' => 'MB275',
            ],
            [
                'name' => 'Basketball Official Size',
                'category' => 'Team Sports',
                'description' => 'Official size 7 basketball with premium grip',
                'price' => 1709.00,
                'compare_at_price' => 2279.00,
                'cost' => 855.00,
                'quantity' => 145,
                'sku_prefix' => 'BOS7',
            ],
        ];

        // Books & Media products (Prices in Philippine Peso - ₱)
        $booksProducts = [
            [
                'name' => 'The Success Mindset',
                'category' => 'Books',
                'description' => 'Bestselling self-help book about achieving your goals',
                'price' => 1424.00,
                'compare_at_price' => 1709.00,
                'cost' => 684.00,
                'quantity' => 88,
                'sku_prefix' => 'TSM',
            ],
            [
                'name' => 'Wireless Headphones Gaming',
                'category' => 'Video Games',
                'description' => '7.1 surround sound gaming headset with mic',
                'price' => 5129.00,
                'compare_at_price' => 6839.00,
                'cost' => 2850.00,
                'quantity' => 62,
                'sku_prefix' => 'WHG71',
            ],
        ];

        // Toys & Kids products (Prices in Philippine Peso - ₱)
        $toysProducts = [
            [
                'name' => 'LEGO Creator Set',
                'category' => 'Toys',
                'description' => '1000-piece building set for ages 8+',
                'price' => 4559.00,
                'compare_at_price' => 5699.00,
                'cost' => 2565.00,
                'quantity' => 75,
                'sku_prefix' => 'LCS1000',
            ],
            [
                'name' => 'Baby Stroller Premium',
                'category' => 'Baby Products',
                'description' => 'Lightweight stroller with adjustable seat and sun canopy',
                'price' => 17099.00,
                'compare_at_price' => 19949.00,
                'cost' => 10260.00,
                'quantity' => 22,
                'sku_prefix' => 'BSP',
            ],
            [
                'name' => 'Kids Sneakers Light-Up',
                'category' => 'Kids Clothing',
                'description' => 'LED light-up shoes, sizes 10-3, multiple colors',
                'price' => 2564.00,
                'compare_at_price' => 3134.00,
                'cost' => 1254.00,
                'quantity' => 95,
                'sku_prefix' => 'KSLU',
            ],
            [
                'name' => 'Educational Science Kit',
                'category' => 'Educational',
                'description' => 'STEM learning kit with 50+ experiments',
                'price' => 3419.00,
                'compare_at_price' => 4559.00,
                'cost' => 1710.00,
                'quantity' => 48,
                'sku_prefix' => 'ESK50',
            ],
        ];

        // Automotive products (Prices in Philippine Peso - ₱)
        $autoProducts = [
            [
                'name' => 'All-Season Tires Set',
                'category' => 'Tires & Wheels',
                'description' => 'Set of 4 premium all-season tires, 225/65R17',
                'price' => 34199.00,
                'compare_at_price' => 39899.00,
                'cost' => 21660.00,
                'quantity' => 24,
                'sku_prefix' => 'AST4',
            ],
            [
                'name' => 'Car Dash Cam',
                'category' => 'Car Accessories',
                'description' => '4K dash camera with night vision and GPS',
                'price' => 8549.00,
                'compare_at_price' => 10829.00,
                'cost' => 4845.00,
                'quantity' => 56,
                'sku_prefix' => 'CDC4K',
            ],
            [
                'name' => 'Mechanic Tool Set',
                'category' => 'Tools & Equipment',
                'description' => '150-piece professional tool set with carrying case',
                'price' => 14249.00,
                'compare_at_price' => 17099.00,
                'cost' => 7980.00,
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

        // Product image mapping (Unsplash collections)
        $categoryImageMap = [
            'Smartphones' => ['technology', 'smartphone', 'phone'],
            'Laptops' => ['laptop', 'computer', 'technology'],
            'Tablets' => ['tablet', 'ipad', 'technology'],
            'Headphones' => ['headphones', 'audio', 'music'],
            'Cameras' => ['camera', 'photography', 'photo'],
            'Smart Watches' => ['smartwatch', 'watch', 'technology'],
            'Men\'s Clothing' => ['mens-fashion', 'clothing', 'fashion'],
            'Women\'s Clothing' => ['womens-fashion', 'dress', 'fashion'],
            'Shoes' => ['shoes', 'sneakers', 'footwear'],
            'Bags & Accessories' => ['bag', 'handbag', 'fashion'],
            'Jewelry' => ['jewelry', 'necklace', 'accessories'],
            'Furniture' => ['furniture', 'interior', 'home'],
            'Kitchen & Dining' => ['kitchen', 'cookware', 'dining'],
            'Bedding' => ['bedroom', 'bed', 'bedding'],
            'Home Decor' => ['home-decor', 'interior', 'decoration'],
            'Skincare' => ['skincare', 'beauty', 'cosmetics'],
            'Makeup' => ['makeup', 'cosmetics', 'beauty'],
            'Hair Care' => ['hair', 'beauty', 'salon'],
            'Fragrances' => ['perfume', 'fragrance', 'scent'],
            'Fitness Equipment' => ['fitness', 'yoga', 'exercise'],
            'Exercise & Fitness' => ['gym', 'fitness', 'workout'],
            'Camping & Hiking' => ['camping', 'outdoor', 'hiking'],
            'Cycling' => ['bicycle', 'cycling', 'bike'],
            'Team Sports' => ['basketball', 'sports', 'game'],
            'Books' => ['book', 'reading', 'library'],
            'Video Games' => ['gaming', 'headset', 'gamer'],
            'Toys' => ['toys', 'lego', 'kids'],
            'Baby Products' => ['baby', 'stroller', 'infant'],
            'Kids Clothing' => ['kids', 'children', 'clothing'],
            'Educational' => ['education', 'learning', 'science'],
            'Tires & Wheels' => ['tire', 'wheel', 'automotive'],
            'Car Accessories' => ['car', 'automotive', 'vehicle'],
            'Tools & Equipment' => ['tools', 'equipment', 'workshop'],
        ];

        // Create products
        foreach ($allProducts as $index => $productData) {
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

            $product = Product::create([
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

            // Add placeholder images using Unsplash
            $categoryName = $productData['category'];
            $keywords = $categoryImageMap[$categoryName] ?? ['product', 'shopping', 'ecommerce'];
            
            // Create 3-5 product images
            $imageCount = rand(3, 5);
            for ($i = 0; $i < $imageCount; $i++) {
                $keyword = $keywords[array_rand($keywords)];
                $randomSeed = $product->id . $i . time();
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => "https://source.unsplash.com/800x800/?{$keyword}&sig={$randomSeed}",
                    'alt_text' => $productData['name'] . ' - Image ' . ($i + 1),
                    'is_primary' => $i === 0, // First image is primary
                    'order' => $i + 1,
                ]);
            }
        }

        $this->command->info('Products seeded successfully!');
        $this->command->info('Total products created: ' . count($allProducts));
        $this->command->info('Product images created: ' . (count($allProducts) * 4) . ' (avg)');
    }
}
