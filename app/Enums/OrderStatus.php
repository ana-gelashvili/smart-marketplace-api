<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Processing = 'processing';
    case Shipping = 'shipping';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
}
