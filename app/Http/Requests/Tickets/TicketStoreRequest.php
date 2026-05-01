<?php

namespace App\Http\Requests\Tickets;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isFromUser = $this->input('from') === 'user';
        $statusId   = (int) $this->input('status_id');

        $rules = [
            'from'        => ['nullable', 'in:user,admin'],
            'user_id'     => ['required', 'exists:users,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'assets_id'   => ['nullable', 'integer'],

            'status_id' => [
    'nullable',
    Rule::exists('statuses', 'id')->where(fn ($q) => $q->where('context', 'ticket')),
],

            'problem'     => ['required', 'string'],
        ];

        if ($isFromUser) {
            $rules['image'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,mp4', 'max:5120'];
            return $rules;
        }

        // admin
        $rules['support_id']  = ['required', 'exists:users,id'];
        $rules['priority_id'] = ['required', 'exists:priorities,id'];
        $rules['image']       = ['nullable', 'file', 'mimes:jpg,jpeg,png,mp4', 'max:10240'];

        if ($statusId === 2) {
            $rules['start_date'] = ['required', 'date'];
        }

        if ($statusId === 3) {
            $rules['start_date'] = ['required', 'date'];
            $rules['end_date']   = ['required', 'date', 'after:start_date'];
            $rules['time_spent_minutes'] = ['required', 'integer', 'min:1'];
            $rules['solution']   = ['required', 'string'];
        }

        // notes optional (kalau ada, minimal 5)
        $rules['notes'] = ['nullable', 'string', 'min:5'];

        return $rules;
    }

    public function messages(): array
    {
        $isFromUser = $this->input('from') === 'user';

        return [
            'user_id.required'     => 'User wajib dipilih',
            'category_id.required' => 'Category wajib dipilih',
            'status_id.required'   => 'Status wajib dipilih',
            'problem.required'     => 'Problem wajib diisi',
            'support_id.required'  => 'IT Support wajib dipilih',
            'priority_id.required' => 'Priority wajib dipilih',

            'image.mimes'          => 'Format file harus JPG, PNG, atau MP4',
            'image.max'            => $isFromUser ? 'Ukuran file maksimal 5MB' : 'Ukuran file maksimal 10MB',

            'start_date.required'  => 'Start Date wajib diisi',
            'end_date.required'    => 'End Date wajib diisi',
            'end_date.after'       => 'End Date harus setelah Start Date',
            'time_spent_minutes.required'  => 'Time Spent wajib diisi',
            'time_spent_minutes.min'       => 'Time Spent minimal 1 menit',
            'solution.required'    => 'Solution wajib diisi',

            'notes.min'            => 'Notes minimal 5 karakter',
        ];
    }
}