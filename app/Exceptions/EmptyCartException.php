<?php

namespace App\Exceptions;

use Exception;

class EmptyCartException extends Exception
{
    public function __construct(string $message = 'Your cart is empty.')
    {
        parent::__construct($message);
    }
}
