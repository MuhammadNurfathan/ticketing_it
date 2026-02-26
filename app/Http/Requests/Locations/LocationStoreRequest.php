<?php

namespace App\Http\Requests\Locations;

use Illuminate\Foundation\Http\FormRequest;

class LocationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255','unique:locations,name',],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lokasi wajib diisi.',
            'name.string'   => 'Nama lokasi harus berupa teks.',
            'name.max'      => 'Nama lokasi maksimal 255 karakter.',
            'name.unique'   => 'Nama lokasi sudah terdaftar.',
        ];
    }
}