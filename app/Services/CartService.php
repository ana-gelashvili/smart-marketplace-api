<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Interfaces\CartServiceInterface;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Member;
use App\Models\Product;

class CartService implements CartServiceInterface
{
    private const ITEM_RELATIONS = ['product.category', 'product.brand', 'product.images'];

    public function getCartForMember(Member $member): Cart
    {
        $cart = $this->getOrCreateCart($member);

        return $cart->load(['items.product.category', 'items.product.brand', 'items.product.images']);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function addItem(Member $member, array $data): CartItem
    {
        $cart = $this->getOrCreateCart($member);
        $product = Product::query()->where('id', $data['product_id'])->firstOrFail();

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        $quantity = ($item->quantity ?? 0) + $data['quantity'];

        if ($quantity > $product->stock) {
            throw new InsufficientStockException;
        }

        if ($item instanceof CartItem) {
            $item->update(['quantity' => $quantity, 'price' => $product->price]);
        } else {
            $item = CartItem::query()->create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        return $item->fresh(self::ITEM_RELATIONS) ?? $item;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateItem(Member $member, int $itemId, array $data): CartItem
    {
        $cart = $this->getOrCreateCart($member);

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('id', $itemId)
            ->firstOrFail();

        if ($data['quantity'] > $item->product->stock) {
            throw new InsufficientStockException;
        }

        $item->update(['quantity' => $data['quantity'], 'price' => $item->product->price]);

        return $item->fresh(self::ITEM_RELATIONS) ?? $item;
    }

    public function removeItem(Member $member, int $itemId): void
    {
        $cart = $this->getOrCreateCart($member);

        CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('id', $itemId)
            ->firstOrFail()
            ->delete();
    }

    private function getOrCreateCart(Member $member): Cart
    {
        return Cart::query()->firstOrCreate(['member_id' => $member->id]);
    }
}
