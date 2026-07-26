<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Exceptions\EmptyCartException;
use App\Exceptions\InsufficientStockException;
use App\Interfaces\OrderServiceInterface;
use App\Models\Cart;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function checkout(Member $member, array $data): Order
    {
        $cart = Cart::query()->with('items')->where('member_id', $member->id)->first();

        if (! $cart instanceof Cart || $cart->items->isEmpty()) {
            throw new EmptyCartException;
        }

        return DB::transaction(function () use ($member, $cart, $data): Order {
            $products = Product::query()
                ->whereIn('id', $cart->items->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($cart->items as $item) {
                $product = $products->get($item->product_id);

                if (! $product instanceof Product || $item->quantity > $product->stock) {
                    throw new InsufficientStockException;
                }
            }

            $subtotal = $cart->items->sum(fn ($item) => (float) $item->price * $item->quantity);
            $shippingCost = 0.0;
            $tax = 0.0;

            $order = Order::query()->create([
                'member_id' => $member->id,
                'shipping_address_id' => $data['shipping_address_id'] ?? null,
                'billing_address_id' => $data['billing_address_id'] ?? null,
                'status' => OrderStatus::Pending,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax' => $tax,
                'total' => $subtotal + $shippingCost + $tax,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                $product = $products->get($item->product_id);

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);

                $product->decrement('stock', $item->quantity);
            }

            $cart->items()->delete();

            return $order->fresh(['items']) ?? $order;
        });
    }

    /**
     * @return LengthAwarePaginator<int, Order>
     */
    public function paginateForMember(Member $member): LengthAwarePaginator
    {
        return Order::query()
            ->where('member_id', $member->id)
            ->with('items')
            ->latest()
            ->paginate(15);
    }

    public function findByUuidForMember(Member $member, string $uuid): Order
    {
        return Order::query()
            ->where('member_id', $member->id)
            ->where('uuid', $uuid)
            ->with('items')
            ->firstOrFail();
    }
}
