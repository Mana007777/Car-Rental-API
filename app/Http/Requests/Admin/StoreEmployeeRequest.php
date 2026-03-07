<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6',
            'phone_number' => 'required|unique:users,phone_number',
            'branch_id'    => 'required|exists:branches,id',
            'role'         => 'required|in:admin,manager,receptionist,mechanic',
            'salary'       => 'nullable|numeric'
        ];
    }

    public function userData()
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ];
    }

    public function employeeData($userId)
    {
        return [
            'user_id' => $userId,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'position' => ucfirst($this->role),
            'password' => Hash::make($this->password),
            'branch_id' => $this->branch_id,
            'role'         => $this->role,
            'hire_date' => now(),
            'salary' => $this->salary,
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email is already taken.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.unique' => 'Phone number is already taken.',
            'branch_id.required' => 'Branch ID is required.',
            'branch_id.exists' => 'Branch ID must exist in branches table.',
            'role.required' => 'Role is required.',
            'role.in' => 'Role must be one of: admin, manager, receptionist, mechanic.',
            'salary.numeric' => 'Salary must be a numeric value.'
        ];
    }
}
