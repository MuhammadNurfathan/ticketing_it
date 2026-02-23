<?php

namespace App\Http\Requests\Departments;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes','required', 'string', 'max:255', 'unique:departments,name'],
            'location_id' => ['sometimes','required', 'exists:locations,id'],
        ];
    }
}
