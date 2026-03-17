<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ReservationApproval\ApproveReservationAction;
use App\Actions\ReservationApproval\DeclineReservationAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\CarReservationResource;
use App\Http\Resources\RentalResource;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ReservationApprovalController extends Controller
{
    use ApiResponse;

    public function approve(int $id, ApproveReservationAction $action)
    {
        try {
            $result = $action->execute($id);

            return $this->success(
                [
                    'reservation' => new CarReservationResource($result['reservation']),
                    'rental' => new RentalResource($result['rental']),
                ],
                'Reservation approved and rental created successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->error('Reservation not found', 404);
        } catch (HttpException $e) {
            return $this->error($e->getMessage(), $e->getStatusCode());
        }
    }

    public function decline(int $id, DeclineReservationAction $action)
    {
        try {
            $reservation = $action->execute($id);

            return $this->success(
                new CarReservationResource($reservation),
                'Reservation declined successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->error('Reservation not found', 404);
        } catch (HttpException $e) {
            return $this->error($e->getMessage(), $e->getStatusCode());
        }
    }
}