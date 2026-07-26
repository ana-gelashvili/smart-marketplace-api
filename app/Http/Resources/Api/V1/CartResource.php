<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Cart */
class CartResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $subtotal = $this->items->sum(fn ($item) => (float) $item->price * $item->quantity);

        return [
            'id' => $this->id,
            'items' => CartItemResource::collection($this->whenLoaded('items')),
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'total_quantity' => (int) $this->items->sum('quantity'),
        ];
    }
}
