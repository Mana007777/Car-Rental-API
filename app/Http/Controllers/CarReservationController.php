<?php

namespace App\Http\Controllers;

use App\Actions\CarReservation\CreateReservationAction;
use App\Actions\CarReservation\DeleteReservationAction;
use App\Actions\CarReservation\ListMyReservationsAction;
use App\Actions\CarReservation\ListReservationsAction;
use App\Actions\CarReservation\ShowReservationAction;
use App\Actions\CarReservation\UpdateReservationAction;
use App\DTOs\CarReservation\ReservationData;
use App\DTOs\CarReservation\ReservationFilterData;
use App\Http\Requests\CarReservationRequest;
use App\Http\Resources\CarReservationResource;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CarReservationController extends Controller
{
    use ApiResponse;

    public function index(Request $request, ListReservationsAction $action)
    {
        return $this->success(
            CarReservationResource::collection($action->execute(ReservationFilterData::fromRequest($request))),
            'Reservations retrieved successfully'
        );
    }

    public function show(int $id, ShowReservationAction $action)
    {
        try {
            return $this->success(
                new CarReservationResource($action->execute($id)),
                'Reservation retrieved successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->error('Reservation not found', 404);
        }
    }

    public function store(CarReservationRequest $request, CreateReservationAction $action)
    {
        try {
            $reservation = $action->execute(ReservationData::fromRequest($request));

            return $this->success(
                new CarReservationResource($reservation),
                'Reservation created successfully',
                201
            );
        } catch (ModelNotFoundException) {
            return $this->error('Car not found', 404);
        } catch (HttpException $e) {
            return $this->error($e->getMessage(), $e->getStatusCode());
        }
    }

    public function update(CarReservationRequest $request, int $id, UpdateReservationAction $action)
    {
        try {
            $reservation = $action->execute($id, ReservationData::fromRequest($request));

            return $this->success(
                new CarReservationResource($reservation),
                'Reservation updated successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->error('Reservation or car not found', 404);
        } catch (HttpException $e) {
            return $this->error($e->getMessage(), $e->getStatusCode());
        }
    }

    public function destroy(int $id, DeleteReservationAction $action)
    {
        try {
            $action->execute($id);

            return $this->success(null, 'Reservation deleted successfully');
        } catch (ModelNotFoundException) {
            return $this->error('Reservation not found', 404);
        } catch (HttpException $e) {
            return $this->error($e->getMessage(), $e->getStatusCode());
        }
    }

    public function myReservations(ListMyReservationsAction $action)
    {
        return $this->success(
            CarReservationResource::collection($action->execute(auth()->id())),
            'Your reservations retrieved successfully'
        );
    }
}