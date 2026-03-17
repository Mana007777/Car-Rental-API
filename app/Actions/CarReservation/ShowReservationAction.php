<?php

namespace App\Actions\CarReservation;

use App\Models\CarReservation;

class ShowReservationAction
{
    public function execute(int $id): CarReservation
    {
        return CarReservation::with(['car.category', 'car.branch', 'customer', 'payments'])->findOrFail($id);
    }
}