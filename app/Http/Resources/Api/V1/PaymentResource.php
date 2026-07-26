<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Payment */
class PaymentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->whenLoaded('order', fn () => $this->order->uuid),
            'gateway' => $this->gateway,
            'method' => $this->method,
            'status' => $this->status->value,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'transaction_id' => $this->transaction_id,
            'meta' => $this->meta,
            'paid_at' => $this->paid_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
