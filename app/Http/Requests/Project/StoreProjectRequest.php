<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'project_code' => 'nullable|string|max:10',
            'project_name' => 'required|string|max:255',
            'requestor_id' => 'required|exists:users,id',
            'priority_id'  => 'required|exists:priorities,id',
            'description'  => 'required|string',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
        ];
    }
}
