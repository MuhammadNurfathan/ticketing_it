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
        $assetId = $this->route('asset')?->id ?? $this->route('asset');

        return [
            'assets_code' => ['required', 'max:255', Rule::unique('assets', 'assets_code')->ignore($assetId)],
            'assets_name' => ['required', 'max:255'],
            'category'    => ['required', 'max:255'],
            'status'      => ['required', 'max:255'],
        ];
    }
}
