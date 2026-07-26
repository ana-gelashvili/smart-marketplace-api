<?php

namespace App\Interfaces;

use App\Models\Member;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

interface PaymentServiceInterface
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function createForMember(Member $member, array $data): Payment;

    public function markSuccessful(Member $member, int $paymentId): Payment;

    public function markFailed(Member $member, int $paymentId): Payment;

    /**
     * @return Collection<int, Payment>
     */
    public function listForOrder(Member $member, string $orderUuid): Collection;
}
