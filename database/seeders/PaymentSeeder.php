<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Order::all() as $order) {
            $paymentStatus = match ($order->status) {
                OrderStatus::Paid, OrderStatus::Processing, OrderStatus::Shipping, OrderStatus::Delivered => PaymentStatus::Completed,
                OrderStatus::Cancelled => PaymentStatus::Failed,
                default => PaymentStatus::Pending,
            };

            Payment::factory()->create([
                'order_id' => $order->id,
                'amount' => $order->total,
                'status' => $paymentStatus,
                'paid_at' => $paymentStatus === PaymentStatus::Completed ? $order->created_at : null,
            ]);
        }
    }
}
