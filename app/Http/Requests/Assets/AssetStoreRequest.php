<?php

namespace App\Http\Requests\Assets;

use Illuminate\Foundation\Http\FormRequest;

class AssetStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'     => ['required', 'string', 'max:255', 'unique:assets,code'],
            'name'     => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'status'    => ['required', 'string', 'max:255'],
            'image'    => ['nullable', 'image', 'max:2048'], // opsional
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode asset wajib diisi',
            'code.unique'   => 'Kode asset sudah digunakan',
            'name.required' => 'Nama asset wajib diisi',
            'category.required' => 'Kategori asset wajib diisi',
        ];
    }
}