<?php

namespace App\Actions\CarReservation;

use App\Exceptions\NotFoundException;
use App\Models\CarReservation;

class ShowReservationAction
{
    public function execute(int $id): CarReservation
    {
        $reservation = CarReservation::with([
            'car.category',
            'car.branch',
            'customer',
            'payments',
        ])->find($id);

        if (! $reservation) {
            throw new NotFoundException('Reservation not found');
        }

        return $reservation;
    }
}