<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255','unique:roles,name',],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama role wajib diisi.',
            'name.string'   => 'Nama role harus berupa teks.',
            'name.max'      => 'Nama role maksimal 255 karakter.',
            'name.unique'   => 'Role dengan nama ini sudah ada.',
        ];
    }
}