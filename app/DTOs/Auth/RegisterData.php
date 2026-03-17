<?php

namespace App\DTOs\Auth;

use App\Http\Requests\Auth\RegisterRequest;

class RegisterData
{
    public function __construct(
        public readonly array $attributes
    ) {}

    public static function fromRequest(RegisterRequest $request): self
    {
        return new self($request->userData());
    }
}