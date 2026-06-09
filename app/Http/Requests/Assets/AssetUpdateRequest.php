<?php

namespace App\Http\Requests\Assets;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $assetId = $this->route('asset') instanceof \App\Models\Assets
            ? $this->route('asset')->id
            : $this->route('asset');

        return [
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('assets', 'code')->ignore($assetId),
            ],
            'name'     => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'status'    => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode asset wajib diisi',
            'code.unique'   => 'Kode asset sudah digunakan',
            'name.required' => 'Nama asset wajib diisi',
            'category.required' => 'Kategori asset wajib diisi',
            'status.required' => 'Status asset wajib diisi',
        ];
    }
}