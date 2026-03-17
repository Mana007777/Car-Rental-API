<?php

namespace App\Actions\Car;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\Exceptions\NotFoundException;
use App\Models\Car;

class DeleteCarAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id): void
    {
        $car = Car::find($id);

        if (! $car) {
            throw new NotFoundException('Car not found');
        }

        $oldValues = $car->toArray();

        $car->delete();

        $this->createAuditLogAction->execute(
            action: 'deleted',
            tableName: 'cars',
            recordId: $id,
            description: 'Car deleted',
            oldValues: $oldValues
        );
    }
}