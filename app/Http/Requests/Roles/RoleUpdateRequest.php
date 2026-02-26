<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes','required','string','max:255',Rule::unique('roles', 'name')->ignore($this->route('role'))],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama role wajib diisi.',
            'name.string'   => 'Nama role harus berupa teks.',
            'name.max'      => 'Nama role maksimal 255 karakter.',
            'name.unique'   => 'Nama role sudah digunakan.',
        ];
    }
}