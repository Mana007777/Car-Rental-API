<?php

namespace App\DTOs\Payment;

use App\Http\Requests\Payment\PaymentRequest;

class PaymentData
{
    public function __construct(
        public readonly int $reservation_id,
        public readonly float $amount,
        public readonly string $payment_method,
    ) {}

    public static function fromRequest(PaymentRequest $request): self
    {
        return new self(
            reservation_id: (int) $request->reservation_id,
            amount: (float) $request->amount,
            payment_method: $request->payment_method,
        );
    }
}