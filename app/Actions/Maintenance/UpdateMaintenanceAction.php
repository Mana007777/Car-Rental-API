<?php

namespace App\Actions\Maintenance;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\DTOs\Maintenance\MaintenanceData;
use App\Models\Maintenance;
use Illuminate\Support\Facades\DB;

class UpdateMaintenanceAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id, MaintenanceData $data)
    {
        $maintenance = Maintenance::with('employee')->findOrFail($id);

        return DB::transaction(function () use ($maintenance, $data) {
            $oldValues = $maintenance->toArray();

            $maintenance->update($data->toArray());
            $maintenance->load('employee');

            $this->createAuditLogAction->execute(
                action: 'updated',
                tableName: 'maintenances',
                recordId: $maintenance->id,
                description: 'Maintenance updated',
                oldValues: $oldValues,
                newValues: $maintenance->fresh()->toArray()
            );

            return $maintenance;
        });
    }
}