<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class PendingRequest extends FormRequest
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
            'reason' => 'required|string|max:255',
            'developer_id' => 'required|exists:users,id',
            'count_to_effective' => 'required|in:0,1',
        ];
    }

}
