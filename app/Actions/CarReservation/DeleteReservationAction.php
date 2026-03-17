<?php

namespace App\Actions\CarReservation;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\Exceptions\NotFoundException;
use App\Exceptions\ReservationNotPendingException;
use App\Exceptions\UnauthorizedException;
use App\Models\CarReservation;

class DeleteReservationAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id): void
    {
        $reservation = CarReservation::find($id);

        if (! $reservation) {
            throw new NotFoundException('Reservation not found');
        }

        if ($reservation->customer_id !== auth()->id()) {
            throw new UnauthorizedException('Unauthorized');
        }

        if ($reservation->status !== 'Pending') {
            throw new ReservationNotPendingException('Only pending reservations can be deleted');
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