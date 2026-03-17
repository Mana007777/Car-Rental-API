<?php

namespace App\DTOs\CarReservation;

use App\Http\Requests\CarReservationRequest;

class ReservationData
{
    public function __construct(
        public readonly array $attributes
    ) {}

    public static function fromRequest(CarReservationRequest $request): self
    {
        return new self($request->reservationData());
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}