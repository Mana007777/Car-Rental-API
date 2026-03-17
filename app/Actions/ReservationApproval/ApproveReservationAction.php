<?php

namespace App\Actions\ReservationApproval;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\Models\CarReservation;
use App\Models\Employee;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApproveReservationAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id): array
    {
        $reservation = CarReservation::with(['car', 'customer', 'payments'])->findOrFail($id);

        if ($reservation->status !== 'Pending') {
            throw new HttpException(422, 'Only pending reservations can be approved');
        }

        if (!$reservation->is_paid) {
            throw new HttpException(422, 'Reservation must be paid before approval');
        }

        if (!$reservation->car) {
            throw new HttpException(404, 'Car not found');
        }

        if ($reservation->car->status !== 'Available') {
            throw new HttpException(422, 'Car is not available');
        }

        $employee = Employee::query()->first();

        if (!$employee) {
            throw new HttpException(422, 'No employee found to assign this rental');
        }

        [$reservation, $rental] = DB::transaction(function () use ($reservation, $employee) {
            $oldReservation = $reservation->toArray();
            $oldCar = $reservation->car->toArray();

            $days = max(
                Carbon::parse($reservation->rental_start_date)
                    ->diffInDays(Carbon::parse($reservation->rental_end_date)),
                1
            );

            $totalAmount = $reservation->car->rental_rate * $days;

            $rental = Rental::create([
                'customer_id' => $reservation->customer_id,
                'car_id' => $reservation->car_id,
                'employee_id' => $employee->id,
                'discount_id' => null,
                'rental_start_date' => $reservation->rental_start_date,
                'rental_end_date' => $reservation->rental_end_date,
                'actual_return_date' => null,
                'total_amount' => $totalAmount,
                'status' => 'Ongoing',
                'insurance_option' => $reservation->insurance_option ? 'Yes' : 'No',
                'fuel_level_start' => 100,
                'fuel_level_end' => null,
            ]);

            $payment = $reservation->payments()->latest()->first();
            if ($payment) {
                $payment->update([
                    'rental_id' => $rental->id,
                ]);
            }

            $reservation->update([
                'status' => 'Approved',
            ]);

            $reservation->car->update([
                'status' => 'Rented',
            ]);

            $this->createAuditLogAction->execute(
                action: 'approved',
                tableName: 'car_reservations',
                recordId: $reservation->id,
                description: 'Reservation approved',
                oldValues: $oldReservation,
                newValues: $reservation->fresh()->toArray()
            );

            $this->createAuditLogAction->execute(
                action: 'created',
                tableName: 'rentals',
                recordId: $rental->id,
                description: 'Rental created from reservation approval',
                newValues: $rental->toArray()
            );

            $this->createAuditLogAction->execute(
                action: 'updated',
                tableName: 'cars',
                recordId: $reservation->car->id,
                description: 'Car marked as rented after reservation approval',
                oldValues: $oldCar,
                newValues: $reservation->car->fresh()->toArray()
            );

            return [$reservation, $rental];
        });

        $reservation->load(['car', 'customer', 'payments']);
        $rental->load(['car', 'customer', 'employee', 'payments']);

        return [
            'reservation' => $reservation,
            'rental' => $rental,
        ];
    }
}