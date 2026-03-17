<?php

namespace App\DTOs\Auth;

use App\Http\Requests\Auth\LoginRequest;

class LoginData
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromRequest(LoginRequest $request): self
    {
        return new self(
            email: $request->email,
            password: $request->password,
        );
    }
}