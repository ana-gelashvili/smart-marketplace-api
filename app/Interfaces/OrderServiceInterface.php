<?php

namespace App\Interfaces;

use App\Models\Member;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderServiceInterface
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function checkout(Member $member, array $data): Order;

    /**
     * @return LengthAwarePaginator<int, Order>
     */
    public function paginateForMember(Member $member): LengthAwarePaginator;

    public function findByUuidForMember(Member $member, string $uuid): Order;
}
