<?php

namespace App\Http\Resources\Api\V1;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CartItem */
class CartItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'line_total' => number_format((float) $this->price * $this->quantity, 2, '.', ''),
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
