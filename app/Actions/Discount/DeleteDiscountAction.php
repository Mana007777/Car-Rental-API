<?php

namespace App\Actions\Discount;

use App\Models\Discount;

class DeleteDiscountAction
{
    public function execute(int $id): void
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
    }
}