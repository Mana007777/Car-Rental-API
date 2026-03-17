<?php

namespace App\DTOs\Employee;

use Illuminate\Http\Request;

class EmployeeFilterData
{
    public function __construct(
        public readonly ?string $search
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->filled('search') ? $request->search : null
        );
    }
}