<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public function logAudit(
        string $action,
        string $tableName,
        ?int $recordId = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        AuditLog::create([
            'user_id' => auth()->id(),
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