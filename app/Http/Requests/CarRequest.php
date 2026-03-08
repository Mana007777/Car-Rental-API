<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class CarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:255",
            "model" => "required|string|max:255",
            "year" => "required|integer|min:1886|max:" . date("Y"),
            'vin' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cars', 'vin')->ignore($this->route('id'))
            ],
            "license_plate" => "required|string|unique:cars,license_plate|max:255",
            "color" => "nullable|string|max:255",
            "mileage" => "required|integer|min:0",
            "status" => "required|in:Available,Rented,Maintenance,Reserved",
            "rental_rate" => "required|numeric|min:0",
            "category_id" => "nullable|exists:vehicle_categories,id",
            "insurance_id" => "nullable|exists:insurances,id",
            "branch_id" => "nullable|exists:branches,id",
            'category.name' => ['required', 'string', 'max:255'],
            'category.description' => ['nullable', 'string'],
        ];
    }
    public function carData(): array
    {
        return $this->safe()->except(['category']);
    }

    public function messages(): array
    {
        return [
            "name.required" => "Car name is required.",
            "model.required" => "Car model is required.",
            "year.required" => "Manufacturing year is required.",
            "year.integer" => "Manufacturing year must be an integer.",
            "year.min" => "Manufacturing year must be at least 1886.",
            "year.max" => "Manufacturing year cannot be in the future.",
            "vin.required" => "VIN is required.",
            "vin.unique" => "VIN must be unique.",
            "license_plate.required" => "License plate is required.",
            "license_plate.unique" => "License plate must be unique.",
            "mileage.required" => "Mileage is required.",
            "mileage.integer" => "Mileage must be an integer.",
            "mileage.min" => "Mileage cannot be negative.",
            "status.required" => "Status is required.",
            "status.in" => "Status must be one of: Available, Rented, Maintenance, Reserved.",
            "rental_rate.required" => "Rental rate is required.",
            "rental_rate.numeric" => "Rental rate must be a number.",
            "rental_rate.min" => "Rental rate cannot be negative."
        ];
    }
}
