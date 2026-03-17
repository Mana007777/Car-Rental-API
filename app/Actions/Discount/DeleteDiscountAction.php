<?php

namespace App\Actions\Discount;

use App\Exceptions\NotFoundException;
use App\Models\Discount;

class DeleteDiscountAction
{
    public function execute(int $id): void
    {
        $discount = Discount::find($id);

        if (! $discount) {
            throw new NotFoundException('Discount not found');
        }

        $discount->delete();
    }
}