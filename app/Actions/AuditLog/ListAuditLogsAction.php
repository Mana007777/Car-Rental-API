<?php

namespace App\Actions\AuditLog;

use App\DTOs\AuditLog\AuditLogFilterData;
use App\Models\AuditLog;

class ListAuditLogsAction
{
    public function execute(AuditLogFilterData $filters)
    {
        return AuditLog::with('user')
            ->when($filters->table_name, fn ($q) => $q->where('table_name', $filters->table_name))
            ->when($filters->action, fn ($q) => $q->where('action', $filters->action))
            ->when($filters->user_id, fn ($q) => $q->where('user_id', $filters->user_id))
            ->when($filters->record_id, fn ($q) => $q->where('record_id', $filters->record_id))
            ->latest('action_date')
            ->paginate(20);
    }
}