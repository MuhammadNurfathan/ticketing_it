<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',

            'username' => 'required|string|max:100|unique:users,username',

            'department_id' => 'required|exists:departments,id',

            'email' => 'required|email|unique:users,email',

            'password' => 'required|string|min:6|confirmed',

            'role_id' => 'required|exists:roles,id',

            'phone' => 'nullable|string|max:20',

            'job_position' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'department_id.required' => 'Department harus dipilih.',
            'department_id.exists' => 'Department tidak valid.',
            'role_id.required' => 'Role harus dipilih.',
            'role_id.exists' => 'Role tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}