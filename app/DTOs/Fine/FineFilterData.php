<?php

namespace App\DTOs\Fine;

use Illuminate\Http\Request;

class FineFilterData
{
    public function __construct(
        public readonly ?bool $paid,
        public readonly ?int $rental_id,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            paid: $request->filled('paid')
                ? filter_var($request->paid, FILTER_VALIDATE_BOOLEAN)
                : null,
            rental_id: $request->filled('rental_id')
                ? (int) $request->rental_id
                : null,
        );
    }
}