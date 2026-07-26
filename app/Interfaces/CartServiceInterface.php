<?php

namespace App\Interfaces;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Member;

interface CartServiceInterface
{
    public function getCartForMember(Member $member): Cart;

    /**
     * @param  array<string, mixed>  $data
     */
    public function addItem(Member $member, array $data): CartItem;

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateItem(Member $member, int $itemId, array $data): CartItem;

    public function removeItem(Member $member, int $itemId): void;
}
