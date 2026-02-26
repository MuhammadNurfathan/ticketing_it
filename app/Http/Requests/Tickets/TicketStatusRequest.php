<?php

namespace App\Http\Requests\Tickets;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_id' => [
                'required',
                Rule::exists('statuses', 'id')->where(fn ($q) => $q->where('context', 'ticket')),
            ],
            'notes'     => ['nullable', 'string', 'min:5'],
            'time_spent'=> ['nullable', 'integer', 'min:0'],
            'solution'  => ['nullable', 'string'],
        ];
    }
}