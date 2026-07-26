<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('stock', '>', 0)->get();
        $members = Member::inRandomOrder()->take(4)->get();

        foreach ($members as $member) {
            $cart = Cart::factory()->create(['member_id' => $member->id]);

            foreach ($products->random(min(random_int(1, 3), $products->count())) as $product) {
                CartItem::factory()->create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => random_int(1, 2),
                    'price' => $product->price,
                ]);
            }
        }
    }
}
