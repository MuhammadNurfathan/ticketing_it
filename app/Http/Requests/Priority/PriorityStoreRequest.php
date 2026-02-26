<?php

namespace App\Http\Requests\Priority;

use Illuminate\Foundation\Http\FormRequest;

class PriorityStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255','unique:priorities,name',],];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama priority wajib diisi.',
            'name.string'   => 'Nama priority harus berupa teks.',
            'name.max'      => 'Nama priority maksimal 255 karakter.',
            'name.unique'   => 'Nama priority sudah terdaftar.',
        ];
    }
}