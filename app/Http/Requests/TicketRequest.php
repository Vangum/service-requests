<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'resolution_notes' => ['nullable', 'string', 'max:255'],
        ];
    }
}
