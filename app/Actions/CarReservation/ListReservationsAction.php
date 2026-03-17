<?php

namespace App\Actions\CarReservation;

use App\DTOs\CarReservation\ReservationFilterData;
use App\Models\CarReservation;

class ListReservationsAction
{
    public function execute(ReservationFilterData $filters)
    {
        return CarReservation::with(['car.category', 'car.branch', 'customer', 'payments'])
            ->when($filters->status, fn ($q) => $q->where('status', $filters->status))
            ->latest()
            ->get();
    }
}