<?php

namespace App\Exceptions;

use Exception;

class OrderNotPayableException extends Exception
{
    public function __construct(string $message = 'Order is not in a payable status.')
    {
        parent::__construct($message);
    }
}
