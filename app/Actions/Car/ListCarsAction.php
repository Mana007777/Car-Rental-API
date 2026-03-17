<?php

namespace App\Actions\Car;

use App\Models\Car;

class ListCarsAction
{
    public function execute()
    {
        return Car::with([
            'category',
            'branch',
            'insurance',
            'maintenances.employee',
            'discount',
        ])->get();
    }
}