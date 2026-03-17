<?php

namespace App\DTOs\Discount;

use Illuminate\Foundation\Http\FormRequest;
    
class DiscountData
{
    public function __construct(
        public readonly array $attributes
    ) {}

    public static function fromRequest(FormRequest $request): self
    {
        return new self($request->discountData());
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}