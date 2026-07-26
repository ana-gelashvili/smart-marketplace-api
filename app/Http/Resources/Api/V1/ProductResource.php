<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'stock' => $this->stock,
            'status' => $this->status->value,
            'featured' => $this->featured,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'images' => $this->whenLoaded('images', fn () => $this->images->map(fn ($image) => [
                'url' => $image->cloudinary_url,
                'type' => $image->type,
            ])),
            'reviews_count' => $this->whenCounted('reviews'),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
