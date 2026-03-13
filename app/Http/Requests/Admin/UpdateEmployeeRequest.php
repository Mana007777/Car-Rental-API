<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'first_name'   => ['sometimes', 'string', 'max:255'],
            'last_name'    => ['sometimes', 'string', 'max:255'],
            'email'        => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password'     => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['sometimes', 'string', 'max:20'],
            'role'         => ['sometimes', 'string'],

            'position'     => ['sometimes', 'string', 'max:255'],
            'branch_id'    => ['nullable', 'exists:branches,id'],
            'hire_date'    => ['nullable', 'date'],
            'salary'       => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function userData(): array
    {
        return collect($this->validated())->only([
            'first_name',
            'last_name',
            'email',
            'password',
            'phone_number',
            'role',
        ])->toArray();
    }

    public function employeeData($userId): array
    {
        return array_merge(
            ['user_id' => $userId],
            collect($this->validated())->only([
                'first_name',
                'last_name',
                'email',
                'password',
                'phone_number',
                'position',
                'branch_id',
                'hire_date',
                'salary',
            ])->toArray()
        );
    }
}