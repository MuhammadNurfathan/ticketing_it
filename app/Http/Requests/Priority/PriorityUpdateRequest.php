<?php

namespace App\Http\Requests\Priority;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PriorityUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes','required','string','max:255',Rule::unique('priorities', 'name')->ignore($this->route('priority')),],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama priority wajib diisi.',
            'name.string'   => 'Nama priority harus berupa teks.',
            'name.max'      => 'Nama priority maksimal 255 karakter.',
            'name.unique'   => 'Nama priority sudah digunakan.',
        ];
    }
}