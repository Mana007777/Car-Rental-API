<?php

namespace App\Actions\Discount;

use App\DTOs\Discount\DiscountData;
use App\Exceptions\NotFoundException;
use App\Models\Discount;

class UpdateDiscountAction
{
    public function execute(int $id, DiscountData $data): Discount
    {
        $discount = Discount::find($id);

        if (! $discount) {
            throw new NotFoundException('Discount not found');
        }

        $discount->update($data->toArray());

        return $discount->fresh();
    }
}