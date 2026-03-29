<?php

namespace App\DTOs\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeData
{
    public function __construct(
        public readonly array $user,
        public readonly array $employee,
    ) {}

    public static function fromRequest(FormRequest $request, ?int $userId = null): self
    {
        return new self(
            user: $request->userData(),
            employee: $userId ? $request->employeeData($userId) : [],
        );
    }
}   