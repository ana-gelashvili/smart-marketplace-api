<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach (Member::all() as $member) {
            $wishlisted = $products->random(min(random_int(0, 4), $products->count()));

            foreach ($wishlisted as $product) {
                Wishlist::factory()->create([
                    'member_id' => $member->id,
                    'product_id' => $product->id,
                ]);
            }
        }
    }
}
