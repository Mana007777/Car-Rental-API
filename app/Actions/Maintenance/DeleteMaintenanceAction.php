<?php

namespace App\Actions\Maintenance;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\Exceptions\NotFoundException;
use App\Models\Maintenance;

class DeleteMaintenanceAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id): void
    {
        $maintenance = Maintenance::find($id);

        if (! $maintenance) {
            throw new NotFoundException('Maintenance not found');
        }

        $oldValues = $maintenance->toArray();

        $maintenance->delete();

        $this->createAuditLogAction->execute(
            action: 'deleted',
            tableName: 'maintenances',
            recordId: $id,
            description: 'Maintenance deleted',
            oldValues: $oldValues
        );
    }
}