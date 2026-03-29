<?php

namespace App\DTOs\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeData
{
    public function __construct(
        public readonly array $user,
    ) {}

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            user: $request->userData(),
        );
    }
}