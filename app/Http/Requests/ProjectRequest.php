<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:3',
            'course_id' => 'required|exists:courses,id',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,medium,high',
            'tags' => 'nullable|string|max:500',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'enable_reminder' => 'nullable|boolean',
            'reminder_days' => 'nullable|integer|min:1|max:30',
            'reminder_time' => 'nullable|date_format:H:i'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del proyecto es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'course_id.required' => 'Debe seleccionar un curso.',
            'course_id.exists' => 'El curso seleccionado no es válido.',
            'description.max' => 'La descripción no puede tener más de 1000 caracteres.',
            'priority.required' => 'Debe seleccionar una prioridad.',
            'priority.in' => 'La prioridad seleccionada no es válida.',
            'tags.max' => 'Las etiquetas no pueden tener más de 500 caracteres.',
            'start_date.date' => 'La fecha de inicio no es válida.',
            'end_date.date' => 'La fecha de entrega no es válida.',
            'end_date.after_or_equal' => 'La fecha de entrega debe ser posterior o igual a la fecha de inicio.',
            'reminder_days.integer' => 'Los días de recordatorio deben ser un número entero.',
            'reminder_days.min' => 'Los días de recordatorio deben ser al menos 1.',
            'reminder_days.max' => 'Los días de recordatorio no pueden ser más de 30.',
            'reminder_time.date_format' => 'El formato de hora no es válido.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'course_id' => 'curso',
            'description' => 'descripción',
            'priority' => 'prioridad',
            'tags' => 'etiquetas',
            'start_date' => 'fecha de inicio',
            'end_date' => 'fecha de entrega',
            'enable_reminder' => 'recordatorio',
            'reminder_days' => 'días de recordatorio',
            'reminder_time' => 'hora de recordatorio',
        ];
    }
} 