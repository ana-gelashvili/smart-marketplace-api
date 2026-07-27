<?php

use App\Enums\ProductStatus;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Wishlist;

beforeEach(function (): void {
    config(['services.ai.key' => 'test-ai-service-key']);
});

test('the ai service can fetch a member profile with a valid service key', function (): void {
    $member = Member::factory()->create();

    $purchasedProduct = Product::factory()->create();
    $order = Order::factory()->for($member)->create();
    OrderItem::factory()->for($order)->for($purchasedProduct, 'product')->create();

    $wishlistedProduct = Product::factory()->create();
    Wishlist::factory()->for($member)->for($wishlistedProduct, 'product')->create();

    $cartProduct = Product::factory()->create();
    $cart = Cart::factory()->for($member)->create();
    CartItem::factory()->for($cart)->for($cartProduct, 'product')->create();

    // Noise: another member's data must not leak into the response.
    $otherMember = Member::factory()->create();
    $otherOrder = Order::factory()->for($otherMember)->create();
    OrderItem::factory()->for($otherOrder)->create();

    $this->withHeader('X-AI-SERVICE-KEY', 'test-ai-service-key')
        ->getJson("/api/v1/ai/members/{$member->id}/profile")
        ->assertOk()
        ->assertJsonPath('member_id', $member->id)
        ->assertJsonCount(1, 'purchases')
        ->assertJsonCount(1, 'wishlist')
        ->assertJsonCount(1, 'cart')
        ->assertJsonPath('purchases.0.product_id', $purchasedProduct->id)
        ->assertJsonPath('purchases.0.category_id', $purchasedProduct->category_id)
        ->assertJsonPath('purchases.0.brand_id', $purchasedProduct->brand_id)
        ->assertJsonPath('wishlist.0.product_id', $wishlistedProduct->id)
        ->assertJsonPath('cart.0.product_id', $cartProduct->id);
});

test('a member profile request without a valid service key is rejected', function (): void {
    $member = Member::factory()->create();

    $this->getJson("/api/v1/ai/members/{$member->id}/profile")->assertUnauthorized();

    $this->withHeader('X-AI-SERVICE-KEY', 'wrong-key')
        ->getJson("/api/v1/ai/members/{$member->id}/profile")
        ->assertUnauthorized();
});

test('the ai service can fetch eligible candidate products with a valid service key', function (): void {
    $eligible = Product::factory()->create(['status' => ProductStatus::Active, 'stock' => 10]);

    Product::factory()->create(['status' => ProductStatus::Inactive, 'stock' => 10]);
    Product::factory()->create(['status' => ProductStatus::Draft, 'stock' => 10]);
    Product::factory()->create(['status' => ProductStatus::Active, 'stock' => 0]);

    $this->withHeader('X-AI-SERVICE-KEY', 'test-ai-service-key')
        ->getJson('/api/v1/ai/products/candidates')
        ->assertOk()
        ->assertJsonCount(1)
        ->assertJson([
            [
                'id' => $eligible->id,
                'category_id' => $eligible->category_id,
                'brand_id' => $eligible->brand_id,
                'status' => 'active',
                'featured' => $eligible->featured,
            ],
        ]);
});

test('a candidate products request without a valid service key is rejected', function (): void {
    $this->getJson('/api/v1/ai/products/candidates')->assertUnauthorized();

    $this->withHeader('X-AI-SERVICE-KEY', 'wrong-key')
        ->getJson('/api/v1/ai/products/candidates')
        ->assertUnauthorized();
});
