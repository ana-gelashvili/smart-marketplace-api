<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $members = Member::inRandomOrder()->take(6)->get();
        $statuses = OrderStatus::cases();

        foreach ($members as $member) {
            $orderCount = random_int(1, 3);

            for ($i = 0; $i < $orderCount; $i++) {
                $orderProducts = $products->random(min(random_int(1, 3), $products->count()));

                $order = Order::factory()->create([
                    'member_id' => $member->id,
                    'subtotal' => 0,
                    'shipping_cost' => 0,
                    'tax' => 0,
                    'total' => 0,
                ]);

                $subtotal = 0.0;

                foreach ($orderProducts as $product) {
                    $quantity = random_int(1, 3);

                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'sku' => $product->sku,
                        'quantity' => $quantity,
                        'price' => $product->price,
                    ]);

                    $subtotal += (float) $product->price * $quantity;
                }

                $order->update([
                    'subtotal' => $subtotal,
                    'total' => $subtotal,
                    'status' => $statuses[array_rand($statuses)],
                ]);
            }
        }
    }
}
