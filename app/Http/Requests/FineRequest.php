<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rental_id' => ['required', 'exists:rentals,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'reason' => ['nullable', 'string'],
            'paid' => ['nullable', 'boolean'],
            'fine_date' => ['nullable', 'date'],
        ];
    }

    public function fineData(): array
    {
        return [
            'rental_id' => $this->rental_id,
            'amount' => $this->amount,
            'reason' => $this->reason,
            'paid' => $this->paid ?? false,
            'fine_date' => $this->fine_date ?? now()->toDateString(),
        ];
    }
}