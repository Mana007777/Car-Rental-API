<?php

namespace App\Actions\Discount;

use App\DTOs\Discount\DiscountData;
use App\Models\Discount;

class UpdateDiscountAction
{
    public function execute(int $id, DiscountData $data): Discount
    {
        $discount = Discount::findOrFail($id);
        $discount->update($data->toArray());

        return $discount->fresh();
    }
}