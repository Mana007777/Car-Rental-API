<?php

namespace App\Actions\Fine;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\Models\Fine;

class DeleteFineAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id): void
    {
        $fine = Fine::findOrFail($id);
        $oldValues = $fine->toArray();

        $fine->delete();

        $this->createAuditLogAction->execute(
            action: 'deleted',
            tableName: 'fines',
            recordId: $id,
            description: 'Fine deleted',
            oldValues: $oldValues
        );
    }
}