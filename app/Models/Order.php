<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Traits\HasUuid;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uuid
 * @property int $member_id
 * @property int|null $shipping_address_id
 * @property int|null $billing_address_id
 * @property OrderStatus $status
 * @property string $subtotal
 * @property string $shipping_cost
 * @property string $tax
 * @property string $total
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['member_id', 'shipping_address_id', 'billing_address_id', 'status', 'subtotal', 'shipping_cost', 'tax', 'total', 'notes'])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory, HasUuid, SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<Member, $this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * @return BelongsTo<MemberAddress, $this>
     */
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(MemberAddress::class, 'shipping_address_id');
    }

    /**
     * @return BelongsTo<MemberAddress, $this>
     */
    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(MemberAddress::class, 'billing_address_id');
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return HasMany<Payment, $this>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
