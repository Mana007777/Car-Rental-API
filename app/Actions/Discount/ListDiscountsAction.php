<?php

namespace App\Actions\Discount;

use App\Models\Discount;

class ListDiscountsAction
{
    public function execute()
    {
        return Discount::latest()->get();
    }
}