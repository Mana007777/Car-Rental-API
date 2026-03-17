<?php

namespace App\Exceptions;

class ConflictException extends ApiException
{
    public function __construct(string $message = 'Conflict detected.')
    {
        parent::__construct($message, 409);
    }
}