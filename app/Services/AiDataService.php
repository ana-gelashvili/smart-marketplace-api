<?php

namespace App\Services;

use App\Enums\ProductStatus;
use App\Interfaces\AiDataServiceInterface;
use App\Models\CartItem;
use App\Models\Member;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Collection;

class AiDataService implements AiDataServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getMemberProfile(Member $member): array
    {
        return [
            'member_id' => $member->id,
            'purchases' => $this->purchasedProducts($member),
            'wishlist' => $this->wishlistedProducts($member),
            'cart' => $this->cartProducts($member),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getCandidateProducts(): array
    {
        return Product::query()
            ->where('status', ProductStatus::Active)
            ->where('stock', '>', 0)
            ->get(['id', 'category_id', 'brand_id', 'status', 'featured'])
            ->map(fn (Product $product) => [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'brand_id' => $product->brand_id,
                'status' => $product->status->value,
                'featured' => $product->featured,
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function purchasedProducts(Member $member): array
    {
        $products = OrderItem::query()
            ->whereHas('order', fn ($query) => $query->where('member_id', $member->id))
            ->whereNotNull('product_id')
            ->with('product:id,category_id,brand_id')
            ->get()
            ->map(fn (OrderItem $item) => $item->product)
            ->filter()
            ->values();

        return $this->mapProducts($products);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function wishlistedProducts(Member $member): array
    {
        $products = $member->wishlistedProducts()->get(['products.id', 'products.category_id', 'products.brand_id']);

        return $this->mapProducts($products);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function cartProducts(Member $member): array
    {
        $products = CartItem::query()
            ->whereHas('cart', fn ($query) => $query->where('member_id', $member->id))
            ->with('product:id,category_id,brand_id')
            ->get()
            ->map(fn (CartItem $item) => $item->product)
            ->filter()
            ->values();

        return $this->mapProducts($products);
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return array<int, array<string, mixed>>
     */
    private function mapProducts(Collection $products): array
    {
        return $products
            ->map(fn (Product $product) => [
                'product_id' => $product->id,
                'category_id' => $product->category_id,
                'brand_id' => $product->brand_id,
            ])
            ->values()
            ->all();
    }
}
