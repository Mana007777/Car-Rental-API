<?php

namespace App\Actions\ReservationApproval;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\Exceptions\BusinessLogicException;
use App\Exceptions\NotFoundException;
use App\Models\CarReservation;

class DeclineReservationAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id): CarReservation
    {
        $reservation = CarReservation::with(['car', 'customer', 'payments'])->find($id);

        if (! $reservation) {
            throw new NotFoundException('Reservation not found');
        }

        if ($reservation->status !== 'Pending') {
            throw new BusinessLogicException('Only pending reservations can be declined');
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