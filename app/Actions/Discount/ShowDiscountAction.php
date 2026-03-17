<?php

namespace App\Actions\Discount;

use App\Exceptions\NotFoundException;
use App\Models\Discount;

class ShowDiscountAction
{
    public function execute(int $id): Discount
    {
        $discount = Discount::find($id);

        if (! $discount) {
            throw new NotFoundException('Discount not found');
        }

        return $discount;
    }
}