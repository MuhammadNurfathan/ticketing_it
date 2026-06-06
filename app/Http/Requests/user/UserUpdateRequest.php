<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => 'required|string|max:150',
            'username' => ['required','string','max:100',Rule::unique('users', 'username')->ignore($userId),],
            'email' => ['required','email',Rule::unique('users', 'email')->ignore($userId),],
            'department_id' => 'required|exists:departments,id',
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'job_position' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'department_id.required' => 'Department harus dipilih.',
            'role_id.required' => 'Role harus dipilih.',
            'email.unique' => 'Email sudah digunakan.',
            'username.unique' => 'Username sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}