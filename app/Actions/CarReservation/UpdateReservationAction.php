<?php

namespace App\Actions\CarReservation;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\DTOs\CarReservation\ReservationData;
use App\Models\Car;
use App\Models\CarReservation;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UpdateReservationAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id, ReservationData $data)
    {
        $reservation = CarReservation::findOrFail($id);

        if ($reservation->customer_id !== auth()->id()) {
            throw new HttpException(403, 'Unauthorized');
        }

        if ($reservation->status !== 'Pending') {
            throw new HttpException(422, 'Only pending reservations can be updated');
        }

        $payload = $data->toArray();
        $car = Car::findOrFail($payload['car_id']);

        if ($car->status !== 'Available' && $reservation->car_id != $car->id) {
            throw new HttpException(422, 'This car is not available for reservation');
        }

        $existingReservation = CarReservation::where('car_id', $payload['car_id'])
            ->whereIn('status', ['Pending', 'Approved'])
            ->where('id', '!=', $reservation->id)
            ->exists();

        if ($existingReservation) {
            throw new HttpException(422, 'This car is already reserved');
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