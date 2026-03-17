<?php

namespace App\Actions\CarReservation;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\Models\CarReservation;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeleteReservationAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id): void
    {
        $reservation = CarReservation::findOrFail($id);

        if ($reservation->customer_id !== auth()->id()) {
            throw new HttpException(403, 'Unauthorized');
        }

        if ($reservation->status !== 'Pending') {
            throw new HttpException(422, 'Only pending reservations can be deleted');
        }

        $oldValues = $reservation->toArray();

        $reservation->delete();

        $this->createAuditLogAction->execute(
            action: 'deleted',
            tableName: 'car_reservations',
            recordId: $id,
            description: 'Reservation deleted',
            oldValues: $oldValues
        );
    }
}