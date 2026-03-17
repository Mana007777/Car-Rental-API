<?php

namespace App\Actions\CarReservation;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\DTOs\CarReservation\ReservationData;
use App\Exceptions\CarNotAvailableException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ReservationConflictException;
use App\Models\Car;
use App\Models\CarReservation;

class CreateReservationAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(ReservationData $data): CarReservation
    {
        $payload = $data->toArray();

        $car = Car::find($payload['car_id']);

        if (! $car) {
            throw new NotFoundException('Car not found');
        }

        if ($car->status !== 'Available') {
            throw new CarNotAvailableException('This car is not available for reservation');
        }

        $exists = CarReservation::where('car_id', $payload['car_id'])
            ->whereIn('status', ['Pending', 'Approved'])
            ->where(function ($query) use ($payload) {
                $query->whereBetween('rental_start_date', [$payload['rental_start_date'], $payload['rental_end_date']])
                    ->orWhereBetween('rental_end_date', [$payload['rental_start_date'], $payload['rental_end_date']])
                    ->orWhere(function ($q) use ($payload) {
                        $q->where('rental_start_date', '<=', $payload['rental_start_date'])
                            ->where('rental_end_date', '>=', $payload['rental_end_date']);
                    });
            })
            ->exists();

        if ($exists) {
            throw new ReservationConflictException('This car is already reserved for the selected dates');
        }

        $reservation = CarReservation::create([
            ...$payload,
            'customer_id' => auth()->id(),
        ]);

        $reservation->load(['car.category', 'car.branch', 'customer', 'payments']);

        $this->createAuditLogAction->execute(
            action: 'created',
            tableName: 'car_reservations',
            recordId: $reservation->id,
            description: 'Reservation created',
            newValues: $reservation->toArray()
        );

        return $reservation;
    }
}