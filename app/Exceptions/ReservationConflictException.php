<?php

namespace App\Exceptions;

class ReservationConflictException extends ConflictException
{
    public function __construct(string $message = 'This car is already reserved for the selected dates.')
    {
        parent::__construct($message);
    }
}