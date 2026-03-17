<?php

namespace App\DTOs\Car;

use App\Http\Requests\CarRequest;

class CarData
{
    public function __construct(
        public readonly array $car,
        public readonly array $category,
        public readonly array $branch,
        public readonly array $insurance,
        public readonly ?array $discount,
    ) {}

    public static function fromRequest(CarRequest $request): self
    {
        return new self(
            car: $request->carData(),
            category: $request->categoryData(),
            branch: $request->branchData(),
            insurance: $request->insuranceData(),
            discount: $request->hasDiscountData() ? $request->discountData() : null,
        );
    }
}