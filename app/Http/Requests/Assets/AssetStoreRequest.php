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
            'assets_code' => ['required', 'max:255', 'unique:assets,assets_code'],
            'assets_name' => ['required', 'max:255'],
            'category'    => ['required', 'max:255'],
            'status'      => ['required', 'max:255'],
        ];
    }
}