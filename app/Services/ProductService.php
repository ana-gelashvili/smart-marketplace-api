<?php

namespace App\Services;

use App\Enums\ProductStatus;
use App\Interfaces\ProductServiceInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService implements ProductServiceInterface
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Product>
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = Product::query()
            ->where('status', ProductStatus::Active)
            ->with(['category', 'brand', 'images']);

        if (! empty($filters['category'])) {
            $category = $filters['category'];
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        if (! empty($filters['brand'])) {
            $brand = $filters['brand'];
            $query->whereHas('brand', fn ($q) => $q->where('slug', $brand));
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        match ($filters['sort'] ?? null) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        return $query->paginate(15)->withQueryString();
    }

    public function findBySlug(string $slug): Product
    {
        return Product::query()
            ->where('slug', $slug)
            ->where('status', ProductStatus::Active)
            ->with(['category', 'brand', 'images'])
            ->withCount('reviews')
            ->firstOrFail();
    }
}
