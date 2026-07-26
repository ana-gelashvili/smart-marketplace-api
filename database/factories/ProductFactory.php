<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = implode(' ', array_map('strval', (array) fake()->unique()->words(3)));

        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-########')),
            'barcode' => fake()->unique()->ean13(),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 5, 500),
            'sale_price' => null,
            'stock' => fake()->numberBetween(0, 100),
            'status' => ProductStatus::Active,
            'featured' => false,
        ];
    }
}
