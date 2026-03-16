<?php

namespace App\DTOs\Fine;

use App\Http\Requests\FineRequest;

class FineData
{
    public function __construct(
        public readonly int $rental_id,
        public readonly float $amount,
        public readonly ?string $reason,
        public readonly bool $paid,
        public readonly string $fine_date,
    ) {}

    public static function fromRequest(FineRequest $request): self
    {
        return new self(
            rental_id: (int) $request->rental_id,
            amount: (float) $request->amount,
            reason: $request->reason,
            paid: (bool) ($request->paid ?? false),
            fine_date: $request->fine_date ?? now()->toDateString(),
        );
    }

    public function toArray(): array
    {
        return [
            'rental_id' => $this->rental_id,
            'amount' => $this->amount,
            'reason' => $this->reason,
            'paid' => $this->paid,
            'fine_date' => $this->fine_date,
        ];
    }
}