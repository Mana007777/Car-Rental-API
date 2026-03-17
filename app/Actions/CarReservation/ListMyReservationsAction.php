<?php

namespace App\Actions\CarReservation;

use App\Models\CarReservation;

class ListMyReservationsAction
{
    public function execute(int $customerId)
    {
        return CarReservation::with(['car.category', 'car.branch', 'customer', 'payments'])
            ->where('customer_id', $customerId)
            ->get();
    }
}