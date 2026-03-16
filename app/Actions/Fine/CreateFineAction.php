<?php

namespace App\Actions\Fine;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\DTOs\Fine\FineData;
use App\Models\Fine;
use App\Models\Rental;

class CreateFineAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(FineData $data): Fine
    {
        Rental::findOrFail($data->rental_id);

        $fine = Fine::create($data->toArray());
        $fine->load('rental');

        $this->createAuditLogAction->execute(
            action: 'created',
            tableName: 'fines',
            recordId: $fine->id,
            description: 'Fine created',
            newValues: $fine->toArray()
        );

        return $fine;
    }
}