<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:1000'],
            'location' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'assigned_to' => ['required', 'exists:users,id'],
            'completion_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'scheduled_at.after' => 'Дата и время выполнения должны быть в будущем.',
        ];
    }

    public function attributes(): array
    {
        return [
            'description' => 'описание задачи',
            'location' => 'место проведения',
            'scheduled_at' => 'дата и время выполнения',
            'assigned_to' => 'назначенный исполнитель',
            'completion_notes' => 'заметки о выполнении',
        ];
    }
}
