<?php

namespace App\Http\Requests\Departments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes','required','string','max:255',Rule::unique('departments', 'name')->ignore($this->route('department')),],
            'location_id' => ['sometimes','required','exists:locations,id',],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama departemen wajib diisi.',
            'name.string'   => 'Nama departemen harus berupa teks.',
            'name.max'      => 'Nama departemen maksimal 255 karakter.',
            'name.unique'   => 'Nama departemen sudah digunakan.',

            'location_id.required' => 'Lokasi wajib dipilih.',
            'location_id.exists'   => 'Lokasi yang dipilih tidak valid.',
        ];
    }
}