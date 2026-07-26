<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Exceptions\InvalidPaymentStatusException;
use App\Exceptions\OrderNotPayableException;
use App\Interfaces\PaymentServiceInterface;
use App\Models\Member;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PaymentService implements PaymentServiceInterface
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function createForMember(Member $member, array $data): Payment
    {
        $order = Order::query()
            ->where('member_id', $member->id)
            ->where('uuid', $data['order_id'])
            ->firstOrFail();

        if ($order->status !== OrderStatus::Pending) {
            throw new OrderNotPayableException;
        }

        $payment = Payment::query()->create([
            'order_id' => $order->id,
            'gateway' => $data['gateway'] ?? null,
            'method' => $data['method'] ?? null,
            'status' => PaymentStatus::Pending,
            'amount' => $order->total,
            'currency' => 'USD',
            'transaction_id' => $data['transaction_id'] ?? null,
            'meta' => $data['meta'] ?? null,
        ]);

        return $payment->fresh(['order']) ?? $payment;
    }

    public function markSuccessful(Member $member, int $paymentId): Payment
    {
        $payment = $this->findOwnedPayment($member, $paymentId);

        if ($payment->status !== PaymentStatus::Pending) {
            throw new InvalidPaymentStatusException;
        }

        return DB::transaction(function () use ($payment): Payment {
            $payment->update([
                'status' => PaymentStatus::Completed,
                'paid_at' => now(),
            ]);

            $payment->order->update(['status' => OrderStatus::Paid]);

            return $payment->fresh(['order']) ?? $payment;
        });
    }

    public function markFailed(Member $member, int $paymentId): Payment
    {
        $payment = $this->findOwnedPayment($member, $paymentId);

        if ($payment->status !== PaymentStatus::Pending) {
            throw new InvalidPaymentStatusException;
        }

        $payment->update(['status' => PaymentStatus::Failed]);

        return $payment->fresh(['order']) ?? $payment;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function listForOrder(Member $member, string $orderUuid): Collection
    {
        $order = Order::query()
            ->where('member_id', $member->id)
            ->where('uuid', $orderUuid)
            ->firstOrFail();

        return Payment::query()
            ->where('order_id', $order->id)
            ->with('order')
            ->latest()
            ->get();
    }

    private function findOwnedPayment(Member $member, int $paymentId): Payment
    {
        return Payment::query()
            ->whereHas('order', fn ($query) => $query->where('member_id', $member->id))
            ->with('order')
            ->findOrFail($paymentId);
    }
}
