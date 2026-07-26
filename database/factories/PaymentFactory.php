<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'gateway' => null,
            'method' => fake()->randomElement(['card', 'paypal', 'cod']),
            'status' => PaymentStatus::Pending,
            'amount' => fake()->randomFloat(2, 20, 500),
            'currency' => 'USD',
            'transaction_id' => null,
            'meta' => null,
            'paid_at' => null,
        ];
    }
}
