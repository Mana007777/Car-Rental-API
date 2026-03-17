<?php

namespace App\Actions\AuditLog;

use App\Models\AuditLog;

class ShowAuditLogAction
{
    public function execute(int $id): AuditLog
    {
        return AuditLog::with('user')->findOrFail($id);
    }
}