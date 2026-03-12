<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'car_id' => ['required', 'exists:cars,id'],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'rental_start_date' => ['required', 'date', 'after_or_equal:reservation_date'],
            'rental_end_date' => ['required', 'date', 'after:rental_start_date'],
            'insurance_option' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(['Pending', 'Approved', 'Declined'])],
        ];
    }

    public function messages(): array
    {
        return [
            'car_id.required' => 'Car is required.',
            'car_id.exists' => 'Selected car does not exist.',
            'reservation_date.required' => 'Reservation date is required.',
            'reservation_date.date' => 'Reservation date must be a valid date.',
            'reservation_date.after_or_equal' => 'Reservation date cannot be in the past.',

            'rental_start_date.required' => 'Rental start date is required.',
            'rental_start_date.date' => 'Rental start date must be a valid date.',
            'rental_start_date.after_or_equal' => 'Rental start date must be on or after reservation date.',

            'rental_end_date.required' => 'Rental end date is required.',
            'rental_end_date.date' => 'Rental end date must be a valid date.',
            'rental_end_date.after' => 'Rental end date must be after rental start date.',

            'status.in' => 'Status must be one of: Pending, Approved, Declined.',
        ];
    }

    public function reservationData(): array
    {
        return [
            'car_id' => $this->car_id,
            'reservation_date' => $this->reservation_date,
            'rental_start_date' => $this->rental_start_date,
            'rental_end_date' => $this->rental_end_date,
            'insurance_option' => $this->insurance_option ?? false,
            'status' => 'Pending',
            'is_paid' => false,
        ];
    }
}