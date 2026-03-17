<?php

namespace App\DTOs\Branch;

use Illuminate\Foundation\Http\FormRequest;

class BranchData
{
    public function __construct(
        public readonly array $attributes
    ) {}

    public static function fromRequest(FormRequest $request): self
    {
        return new self($request->validated());
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}