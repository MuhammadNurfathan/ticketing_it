<?php
namespace App\Http\Requests\Status;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StatusUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('status');

        return [
            'name' => ['required','string','max:50'],
            'type' => ['required','string',Rule::unique('statuses')->where('context', $this->context)->ignore($id),],
            'context' => ['required','in:ticket,project'],
        ];
    }
}