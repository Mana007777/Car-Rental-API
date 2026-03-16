<?php

namespace App\Actions\Maintenance;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\DTOs\Maintenance\MaintenanceData;
use App\Models\Car;
use Illuminate\Support\Facades\DB;

class CreateMaintenanceAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $carId, MaintenanceData $data)
    {
        $car = Car::findOrFail($carId);

        return DB::transaction(function () use ($car, $data) {
            $maintenance = $car->maintenances()->create($data->toArray());
            $maintenance->load('employee');

            $this->createAuditLogAction->execute(
                action: 'created',
                tableName: 'maintenances',
                recordId: $maintenance->id,
                description: 'Maintenance created',
                newValues: $maintenance->toArray()
            );

            return $maintenance;
        });
    }
}