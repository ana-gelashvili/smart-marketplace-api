<?php

namespace App\Exceptions;

use Exception;

class InvalidPaymentStatusException extends Exception
{
    public function __construct(string $message = 'This payment cannot transition from its current status.')
    {
        parent::__construct($message);
    }
}
