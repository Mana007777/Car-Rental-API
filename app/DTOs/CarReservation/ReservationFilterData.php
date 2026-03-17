<?php

namespace App\DTOs\CarReservation;

use Illuminate\Http\Request;

class ReservationFilterData
{
    public function __construct(
        public readonly ?string $status
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            status: $request->filled('status') ? $request->status : null
        );
    }
}