<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class DoneProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'description'            => 'nullable|string',
            'developer_id'           => 'nullable|exists:users,id',
            'apply_pending_duration' => 'required|in:0,1',
        ];
    }
}
