<?php

namespace App\Actions\AuditLog;

use App\Models\AuditLog;

class CreateAuditLogAction
{
    public function execute(
        string $action,
        string $tableName,
        ?int $recordId = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null
    ): void {
        AuditLog::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'action_date' => now(),
        ]);
    }
}