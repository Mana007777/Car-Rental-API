<?php

namespace App\Actions\Fine;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\DTOs\Fine\FineData;
use App\Exceptions\NotFoundException;
use App\Models\Fine;
use App\Models\Rental;

class UpdateFineAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id, FineData $data): Fine
    {
        $fine = Fine::with('rental')->find($id);

        if (! $fine) {
            throw new NotFoundException('Fine not found');
        }

        $rental = Rental::find($data->rental_id);

        if (! $rental) {
            throw new NotFoundException('Rental not found');
        }

        $oldValues = $fine->toArray();

        $fine->update($data->toArray());
        $fine->load('rental');

        $this->createAuditLogAction->execute(
            action: 'updated',
            tableName: 'fines',
            recordId: $fine->id,
            description: 'Fine updated',
            oldValues: $oldValues,
            newValues: $fine->fresh()->toArray()
        );

        return $fine;
    }
}