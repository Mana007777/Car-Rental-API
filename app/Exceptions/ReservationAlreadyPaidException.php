<?php

namespace App\Exceptions;

class ReservationAlreadyPaidException extends BusinessLogicException
{
    public function __construct(string $message = 'This reservation is already paid.')
    {
        parent::__construct($message, 422);
    }
}