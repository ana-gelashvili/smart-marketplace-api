<?php

namespace Database\Seeders;

use App\Enums\ProductStatus;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brands = Brand::all();

        /** @var array<int, array{0: string, 1: string, 2: float}> $catalog */
        $catalog = [
            ['smartphones', 'iPhone 15 Pro', 999.00],
            ['smartphones', 'Samsung Galaxy S24', 899.00],
            ['smartphones', 'Google Pixel 8', 699.00],
            ['phone-accessories', 'Leather Phone Case', 39.00],
            ['phone-accessories', 'Wireless Charging Pad', 29.00],
            ['phone-accessories', 'Tempered Glass Screen Protector', 14.00],
            ['laptops', 'MacBook Pro 14"', 1999.00],
            ['laptops', 'Dell XPS 13', 1299.00],
            ['laptops', 'Lenovo ThinkPad X1 Carbon', 1499.00],
            ['mens-clothing', "Men's Denim Jacket", 79.00],
            ['mens-clothing', "Men's Cotton T-Shirt", 19.00],
            ['mens-clothing', "Men's Chino Pants", 49.00],
            ['womens-clothing', "Women's Summer Dress", 59.00],
            ['womens-clothing', "Women's Yoga Leggings", 39.00],
            ['womens-clothing', "Women's Wool Sweater", 69.00],
            ['home-garden', 'Ceramic Plant Pot', 24.00],
            ['home-garden', 'LED Desk Lamp', 34.00],
            ['home-garden', 'Stainless Steel Cookware Set', 149.00],
        ];

        $featuredIndexes = [0, 6, 9, 15];
        $draftIndexes = [4, 11, 17];
        $inactiveIndexes = [5, 13];

        foreach ($catalog as $index => [$categorySlug, $name, $price]) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();

            $status = match (true) {
                in_array($index, $draftIndexes, true) => ProductStatus::Draft,
                in_array($index, $inactiveIndexes, true) => ProductStatus::Inactive,
                default => ProductStatus::Active,
            };

            $product = Product::factory()->create([
                'category_id' => $category->id,
                'brand_id' => $brands->random()->id,
                'name' => $name,
                'slug' => Str::slug($name),
                'price' => $price,
                'sale_price' => $index % 5 === 0 ? round($price * 0.85, 2) : null,
                'stock' => random_int(0, 50),
                'status' => $status,
                'featured' => in_array($index, $featuredIndexes, true),
            ]);

            ProductImage::factory()
                ->count(random_int(1, 3))
                ->sequence(
                    ['type' => 'primary'],
                    ['type' => 'gallery'],
                    ['type' => 'gallery'],
                )
                ->create(['product_id' => $product->id]);
        }
    }
}
