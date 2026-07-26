<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Member;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 500);
        $shippingCost = fake()->randomFloat(2, 0, 20);
        $tax = round($subtotal * 0.1, 2);

        return [
            'member_id' => Member::factory(),
            'shipping_address_id' => null,
            'billing_address_id' => null,
            'status' => OrderStatus::Pending,
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'tax' => $tax,
            'total' => $subtotal + $shippingCost + $tax,
            'notes' => null,
        ];
    }
}
