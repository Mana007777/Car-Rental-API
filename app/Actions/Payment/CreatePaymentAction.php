<?php

namespace App\Actions\Payment;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\DTOs\Payment\PaymentData;
use App\Exceptions\BusinessLogicException;
use App\Exceptions\CarNotAvailableException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ReservationAlreadyPaidException;
use App\Exceptions\ReservationNotPendingException;
use App\Exceptions\UnauthorizedException;
use App\Models\CarReservation;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreatePaymentAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(PaymentData $data): Payment
    {
        $reservation = CarReservation::with('car', 'payments')->find($data->reservation_id);

        if (! $reservation) {
            throw new NotFoundException('Reservation not found');
        }

        if ($reservation->customer_id !== auth()->id()) {
            throw new UnauthorizedException('You cannot pay for this reservation');
        }

        if ($reservation->status !== 'Pending') {
            throw new ReservationNotPendingException('Only pending reservations can be paid');
        }

        if ($reservation->is_paid) {
            throw new ReservationAlreadyPaidException('This reservation is already paid');
        }

        if (! $reservation->car) {
            throw new NotFoundException('Car not found for this reservation');
        }

        if ($reservation->car->status !== 'Available') {
            throw new CarNotAvailableException('This car is not available');
        }

        $days = max(
            Carbon::parse($reservation->rental_start_date)
                ->diffInDays(Carbon::parse($reservation->rental_end_date)),
            1
        );

        $requiredAmount = $reservation->car->rental_rate * $days;

        if ((float) $data->amount < (float) $requiredAmount) {
            throw new BusinessLogicException('Paid amount is less than required rental amount');
        }

        $oldReservationValues = $reservation->toArray();

        $payment = DB::transaction(function () use ($data, $reservation) {
            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'rental_id' => null,
                'payment_date' => now()->toDateString(),
                'amount' => $data->amount,
                'payment_method' => $data->payment_method,
                'status' => 'Completed',
            ]);

            $reservation->update([
                'is_paid' => true,
            ]);

            return $payment;
        });

        $this->createAuditLogAction->execute(
            action: 'paid',
            tableName: 'payments',
            recordId: $payment->id,
            description: 'Reservation payment completed',
            newValues: $payment->toArray()
        );

        $this->createAuditLogAction->execute(
            action: 'updated',
            tableName: 'car_reservations',
            recordId: $reservation->id,
            description: 'Reservation marked as paid',
            oldValues: $oldReservationValues,
            newValues: $reservation->fresh()->toArray()
        );

        return $payment;
    }
}