<?php

namespace App\Actions\Car;

use App\Exceptions\NotFoundException;
use App\Models\Car;

class ShowCarAction
{
    public function execute(int $id): Car
    {
        $car = Car::with([
            'category',
            'branch',
            'insurance',
            'maintenances.employee',
            'discount',
        ])->find($id);

        if (! $car) {
            throw new NotFoundException('Car not found');
        }

        return $car;
    }
}