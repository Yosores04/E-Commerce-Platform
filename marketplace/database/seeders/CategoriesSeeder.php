<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Latest electronics and gadgets',
                'icon' => '📱',
                'status' => 'active',
                'order' => 1,
                'children' => [
                    ['name' => 'Smartphones', 'slug' => 'smartphones', 'icon' => '📱'],
                    ['name' => 'Laptops', 'slug' => 'laptops', 'icon' => '💻'],
                    ['name' => 'Tablets', 'slug' => 'tablets', 'icon' => '📲'],
                    ['name' => 'Headphones', 'slug' => 'headphones', 'icon' => '🎧'],
                    ['name' => 'Cameras', 'slug' => 'cameras', 'icon' => '📷'],
                    ['name' => 'Smart Watches', 'slug' => 'smart-watches', 'icon' => '⌚'],
                ]
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Trendy fashion and apparel',
                'icon' => '👔',
                'status' => 'active',
                'order' => 2,
                'children' => [
                    ['name' => 'Men\'s Clothing', 'slug' => 'mens-clothing', 'icon' => '👔'],
                    ['name' => 'Women\'s Clothing', 'slug' => 'womens-clothing', 'icon' => '👗'],
                    ['name' => 'Shoes', 'slug' => 'shoes', 'icon' => '👟'],
                    ['name' => 'Bags', 'slug' => 'bags', 'icon' => '👜'],
                    ['name' => 'Accessories', 'slug' => 'accessories', 'icon' => '👒'],
                    ['name' => 'Jewelry', 'slug' => 'jewelry', 'icon' => '💍'],
                ]
            ],
            [
                'name' => 'Home & Living',
                'slug' => 'home-living',
                'description' => 'Everything for your home',
                'icon' => '🏠',
                'status' => 'active',
                'order' => 3,
                'children' => [
                    ['name' => 'Furniture', 'slug' => 'furniture', 'icon' => '🛋️'],
                    ['name' => 'Kitchen', 'slug' => 'kitchen', 'icon' => '🍳'],
                    ['name' => 'Bedding', 'slug' => 'bedding', 'icon' => '🛏️'],
                    ['name' => 'Decor', 'slug' => 'decor', 'icon' => '🖼️'],
                    ['name' => 'Storage', 'slug' => 'storage', 'icon' => '📦'],
                ]
            ],
            [
                'name' => 'Beauty & Health',
                'slug' => 'beauty-health',
                'description' => 'Beauty and health products',
                'icon' => '💄',
                'status' => 'active',
                'order' => 4,
                'children' => [
                    ['name' => 'Skincare', 'slug' => 'skincare', 'icon' => '🧴'],
                    ['name' => 'Makeup', 'slug' => 'makeup', 'icon' => '💄'],
                    ['name' => 'Hair Care', 'slug' => 'hair-care', 'icon' => '💇'],
                    ['name' => 'Fragrances', 'slug' => 'fragrances', 'icon' => '🌸'],
                    ['name' => 'Fitness', 'slug' => 'fitness', 'icon' => '🏋️'],
                ]
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'description' => 'Sports equipment and outdoor gear',
                'icon' => '⚽',
                'status' => 'active',
                'order' => 5,
                'children' => [
                    ['name' => 'Exercise Equipment', 'slug' => 'exercise-equipment', 'icon' => '🏋️'],
                    ['name' => 'Camping', 'slug' => 'camping', 'icon' => '⛺'],
                    ['name' => 'Cycling', 'slug' => 'cycling', 'icon' => '🚴'],
                    ['name' => 'Team Sports', 'slug' => 'team-sports', 'icon' => '⚽'],
                    ['name' => 'Water Sports', 'slug' => 'water-sports', 'icon' => '🏊'],
                ]
            ],
            [
                'name' => 'Books & Media',
                'slug' => 'books-media',
                'description' => 'Books, music, and entertainment',
                'icon' => '📚',
                'status' => 'active',
                'order' => 6,
                'children' => [
                    ['name' => 'Books', 'slug' => 'books', 'icon' => '📖'],
                    ['name' => 'E-books', 'slug' => 'ebooks', 'icon' => '📱'],
                    ['name' => 'Music', 'slug' => 'music', 'icon' => '🎵'],
                    ['name' => 'Movies', 'slug' => 'movies', 'icon' => '🎬'],
                    ['name' => 'Games', 'slug' => 'games', 'icon' => '🎮'],
                ]
            ],
            [
                'name' => 'Toys & Kids',
                'slug' => 'toys-kids',
                'description' => 'Toys and products for children',
                'icon' => '🧸',
                'status' => 'active',
                'order' => 7,
                'children' => [
                    ['name' => 'Toys', 'slug' => 'toys', 'icon' => '🧸'],
                    ['name' => 'Baby Products', 'slug' => 'baby-products', 'icon' => '👶'],
                    ['name' => 'Kids Clothing', 'slug' => 'kids-clothing', 'icon' => '👕'],
                    ['name' => 'Educational', 'slug' => 'educational', 'icon' => '📚'],
                ]
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'description' => 'Car parts and accessories',
                'icon' => '🚗',
                'status' => 'active',
                'order' => 8,
                'children' => [
                    ['name' => 'Car Parts', 'slug' => 'car-parts', 'icon' => '🔧'],
                    ['name' => 'Car Accessories', 'slug' => 'car-accessories', 'icon' => '🚗'],
                    ['name' => 'Tools', 'slug' => 'tools', 'icon' => '🛠️'],
                    ['name' => 'Tires', 'slug' => 'tires', 'icon' => '⚙️'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $category = Category::create($categoryData);

            foreach ($children as $childData) {
                $childData['parent_id'] = $category->id;
                $childData['status'] = true;
                Category::create($childData);
            }
        }

        $this->command->info('Categories seeded successfully!');
    }
}

