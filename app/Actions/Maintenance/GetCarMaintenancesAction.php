<?php

namespace App\Actions\Maintenance;

use App\Models\Car;

class GetCarMaintenancesAction
{
    public function execute(int $carId)
    {
        $car = Car::findOrFail($carId);

        return $car->maintenances()
            ->with('employee')
            ->latest()
            ->get();
    }
}