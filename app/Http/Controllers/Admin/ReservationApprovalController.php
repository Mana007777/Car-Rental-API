<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ReservationApproval\ApproveReservationAction;
use App\Actions\ReservationApproval\DeclineReservationAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\CarReservationResource;
use App\Http\Resources\RentalResource;
use App\Traits\ApiResponse;

class ReservationApprovalController extends Controller
{
    use ApiResponse;

    public function approve(int $id, ApproveReservationAction $action)
    {
        $result = $action->execute($id);

        return $this->success(
            [
                'reservation' => new CarReservationResource($result['reservation']),
                'rental' => new RentalResource($result['rental']),
            ],
            'Reservation approved and rental created successfully'
        );
    }

    public function decline(int $id, DeclineReservationAction $action)
    {
        $reservation = $action->execute($id);

        return $this->success(
            new CarReservationResource($reservation),
            'Reservation declined successfully'
        );
    }
}