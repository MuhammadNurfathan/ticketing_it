<?php
namespace App\Http\Requests\Status;

use Illuminate\Foundation\Http\FormRequest;

class StatusStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:50'],
            'type'    => ['required', 'string', 'max:50'],
            'context' => ['required', 'in:ticket,project'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Nama status wajib diisi',
            'type.required'    => 'Type status wajib diisi',
            'context.required' => 'Context wajib diisi',
            'context.in'       => 'Context hanya boleh ticket atau project',
        ];
    }
}