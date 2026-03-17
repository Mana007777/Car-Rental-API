<?php

namespace App\Actions\AuditLog;

use App\Exceptions\NotFoundException;
use App\Models\AuditLog;

class ShowAuditLogAction
{
    public function execute(int $id): AuditLog
    {
        $log = AuditLog::with('user')->find($id);

        if (! $log) {
            throw new NotFoundException('Audit log not found');
        }

        return $log;
    }
}