<?php

namespace App\Actions\Car;

use App\Models\Car;

class ShowCarAction
{
    public function execute(int $id): Car
    {
        return Car::with([
            'category',
            'branch',
            'insurance',
            'maintenances.employee',
            'discount',
        ])->findOrFail($id);
    }
}