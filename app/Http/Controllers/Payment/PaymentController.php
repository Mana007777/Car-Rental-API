<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\RentalResource;
use App\Models\CarReservation;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Rental;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    use ApiResponse;

    public function store(PaymentRequest $request)
    {
        $reservation = CarReservation::with('car')->find($request->reservation_id);

        if (!$reservation) {
            return $this->error('Reservation not found', 404);
        }

        if ($reservation->customer_id !== auth()->id()) {
            return $this->error('You cannot pay for this reservation', 403);
        }

        if (!$reservation->car) {
            return $this->error('Car not found for this reservation', 404);
        }

        if ($reservation->car->status !== 'Available') {
            return $this->error('This car is not available for rent', 422);
        }

        $car = $reservation->car;
        $days = max(
            Carbon::parse($request->rental_start)
                ->diffInDays(Carbon::parse($request->rental_end)),
            1
        );

        $totalAmount = $car->rental_rate * $days;

        if ((float) $request->amount < (float) $totalAmount) {
            return $this->error('Paid amount is less than required rental amount', 422);
        }

        $employee = $this->resolveEmployee();

        if (!$employee) {
            return $this->error('No employee found to assign this rental', 422);
        }

        [$rental, $payment] = DB::transaction(function () use ($request, $reservation, $car, $employee, $totalAmount) {
            $rental = Rental::create([
                'customer_id' => auth()->id(),
                'car_id' => $car->id,
                'employee_id' => $employee->id,
                'discount_id' => null,
                'rental_start_date' => $request->rental_start,
                'rental_end_date' => $request->rental_end,
                'actual_return_date' => null,
                'total_amount' => $totalAmount,
                'status' => 'Ongoing',
                'insurance_option' => ($request->insurance_option ?? false) ? 'Yes' : 'No',
                'fuel_level_start' => $request->fuel_level_start ?? 100,
                'fuel_level_end' => null,
            ]);

            $payment = Payment::create([
                'rental_id' => $rental->id,
                'payment_date' => now()->toDateString(),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'status' => 'Completed',
            ]);

            $car->update([
                'status' => 'Rented',
            ]);

            $reservation->update([
                'status' => 'Confirmed',
            ]);

            return [$rental, $payment];
        });

        $rental->load(['car', 'customer', 'employee', 'payments']);

        return $this->success(
            'Payment completed and rental created successfully',
            [
                'rental' => new RentalResource($rental),
                'payment' => new PaymentResource($payment),
            ],
            201
        );
    }

    private function resolveEmployee(): ?Employee
    {
        return Employee::query()->first();
    }
}