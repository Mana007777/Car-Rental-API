<?php

namespace App\Actions\ReservationApproval;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\Models\CarReservation;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeclineReservationAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id): CarReservation
    {
        $reservation = CarReservation::with(['car', 'customer', 'payments'])->findOrFail($id);

        if ($reservation->status !== 'Pending') {
            throw new HttpException(422, 'Only pending reservations can be declined');
        }

        $oldValues = $reservation->toArray();

        $reservation->update([
            'status' => 'Declined',
        ]);

        $this->createAuditLogAction->execute(
            action: 'declined',
            tableName: 'car_reservations',
            recordId: $reservation->id,
            description: 'Reservation declined',
            oldValues: $oldValues,
            newValues: $reservation->fresh()->toArray()
        );

        return $reservation->load(['car', 'customer', 'payments']);
    }
}