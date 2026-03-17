<?php

namespace App\Actions\Discount;

use App\Models\Discount;

class ShowDiscountAction
{
    public function execute(int $id): Discount
    {
        return Discount::findOrFail($id);
    }
}