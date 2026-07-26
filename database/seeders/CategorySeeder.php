<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
        $phones = Category::create(['name' => 'Phones', 'slug' => 'phones', 'parent_id' => $electronics->id]);
        Category::create(['name' => 'Smartphones', 'slug' => 'smartphones', 'parent_id' => $phones->id]);
        Category::create(['name' => 'Phone Accessories', 'slug' => 'phone-accessories', 'parent_id' => $phones->id]);
        Category::create(['name' => 'Laptops', 'slug' => 'laptops', 'parent_id' => $electronics->id]);

        $fashion = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
        Category::create(['name' => "Men's Clothing", 'slug' => 'mens-clothing', 'parent_id' => $fashion->id]);
        Category::create(['name' => "Women's Clothing", 'slug' => 'womens-clothing', 'parent_id' => $fashion->id]);

        Category::create(['name' => 'Home & Garden', 'slug' => 'home-garden']);
    }
}
