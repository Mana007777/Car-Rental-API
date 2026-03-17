<?php

namespace App\Actions\CarReservation;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\DTOs\CarReservation\ReservationData;
use App\Exceptions\CarNotAvailableException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ReservationConflictException;
use App\Exceptions\ReservationNotPendingException;
use App\Exceptions\UnauthorizedException;
use App\Models\Car;
use App\Models\CarReservation;

class UpdateReservationAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id, ReservationData $data): CarReservation
    {
        $reservation = CarReservation::find($id);

        if (! $reservation) {
            throw new NotFoundException('Reservation not found');
        }

        if ($reservation->customer_id !== auth()->id()) {
            throw new UnauthorizedException('Unauthorized');
        }

        if ($reservation->status !== 'Pending') {
            throw new ReservationNotPendingException('Only pending reservations can be updated');
        }

        $payload = $data->toArray();

        $car = Car::find($payload['car_id']);

        if (! $car) {
            throw new NotFoundException('Car not found');
        }

        if ($car->status !== 'Available' && $reservation->car_id != $car->id) {
            throw new CarNotAvailableException('This car is not available for reservation');
        }

        $exists = CarReservation::where('car_id', $payload['car_id'])
            ->whereIn('status', ['Pending', 'Approved'])
            ->where('id', '!=', $reservation->id)
            ->exists();

        if ($exists) {
            throw new ReservationConflictException('This car is already reserved');
        }

        $oldValues = $reservation->toArray();

        $reservation->update([
            ...$payload,
            'customer_id' => $reservation->customer_id,
            'is_paid' => $reservation->is_paid,
        ]);

        $reservation->load(['car.category', 'car.branch', 'customer', 'payments']);

        $this->createAuditLogAction->execute(
            action: 'updated',
            tableName: 'car_reservations',
            recordId: $reservation->id,
            description: 'Reservation updated',
            oldValues: $oldValues,
            newValues: $reservation->fresh()->toArray()
        );

        return $reservation;
    }
}