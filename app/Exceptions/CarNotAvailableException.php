<?php

namespace App\Exceptions;

class CarNotAvailableException extends BusinessLogicException
{
    public function __construct(string $message = 'This car is not available.')
    {
        parent::__construct($message, 422);
    }
}