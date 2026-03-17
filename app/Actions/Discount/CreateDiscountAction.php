<?php

namespace App\Actions\Discount;

use App\DTOs\Discount\DiscountData;
use App\Models\Discount;

class CreateDiscountAction
{
    public function execute(DiscountData $data): Discount
    {
        return Discount::create($data->toArray());
    }
}