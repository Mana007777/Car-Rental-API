<?php

namespace App\Exceptions;

class ReservationNotPendingException extends BusinessLogicException
{
    public function __construct(string $message = 'Only pending reservations can perform this action.')
    {
        parent::__construct($message, 422);
    }
}