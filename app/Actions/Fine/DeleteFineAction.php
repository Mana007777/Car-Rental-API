<?php

namespace App\Actions\Fine;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\Exceptions\NotFoundException;
use App\Models\Fine;

class DeleteFineAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id): void
    {
        $fine = Fine::find($id);

        if (! $fine) {
            throw new NotFoundException('Fine not found');
        }

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