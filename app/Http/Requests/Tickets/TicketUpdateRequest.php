<?php

namespace App\Http\Requests\Tickets;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $statusId = (int) $this->input('status_id');

        $rules = [
            'support_id'  => ['required', 'exists:users,id'],
            'priority_id' => ['required', 'exists:priorities,id'],
            'assets_id'   => ['nullable', 'integer'],

            'status_id'   => [
                'required',
                Rule::exists('statuses', 'id')->where(fn ($q) => $q->where('context', 'ticket')),
            ],

            'notes'       => ['nullable', 'string', 'min:5'],
        ];

        if ($statusId === 2) {
            $rules['start_date'] = ['required', 'date'];
        }

        if ($statusId === 3) {
            $rules['start_date'] = ['required', 'date'];
            $rules['end_date']   = ['required', 'date', 'after:start_date'];
            $rules['time_spent_minutes'] = ['required', 'integer', 'min:1'];
            $rules['solution']   = ['required', 'string'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'support_id.required'  => 'IT Support wajib dipilih',
            'status_id.required'   => 'Status wajib dipilih',
            'priority_id.required' => 'Priority wajib dipilih',
            'start_date.required'  => 'Start Date wajib diisi',
            'end_date.required'    => 'End Date wajib diisi untuk status Done',
            'end_date.after'       => 'End Date harus setelah Start Date',
            'time_spent_minutes.required'  => 'Time Spent wajib diisi',
            'solution.required'    => 'Solution wajib diisi',
            'notes.min'            => 'Notes minimal 5 karakter',
        ];
    }
}