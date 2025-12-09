<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
        $rules = [
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::exists('courses', 'id')->where('user_id', auth()->id())
            ],
            'title' => 'required|string|max:255|min:3',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:task,exam,project,reading',
            'start_date' => 'required|date|before_or_equal:due_date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high'
        ];

        // Agregar validación de status solo si se está enviando
        if ($this->has('status')) {
            $rules['status'] = 'required|in:pending,completed';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        $messages = [
            'course_id.required' => 'Debe seleccionar un curso.',
            'course_id.exists' => 'El curso seleccionado no existe o no tiene permisos.',
            'title.required' => 'El título de la tarea es obligatorio.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'title.max' => 'El título no puede tener más de 255 caracteres.',
            'description.max' => 'La descripción no puede tener más de 1000 caracteres.',
            'type.required' => 'Debe seleccionar un tipo de tarea.',
            'type.in' => 'El tipo de tarea seleccionado no es válido.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.before_or_equal' => 'La fecha de inicio debe ser anterior o igual a la fecha de vencimiento.',
            'due_date.required' => 'La fecha de vencimiento es obligatoria.',
            'due_date.after_or_equal' => 'La fecha de vencimiento debe ser posterior o igual a la fecha de inicio.',
            'priority.required' => 'Debe seleccionar una prioridad.',
            'priority.in' => 'La prioridad seleccionada no es válida.',
        ];

        // Agregar mensajes para status si se está validando
        if ($this->has('status')) {
            $messages['status.required'] = 'Debe seleccionar un estado.';
            $messages['status.in'] = 'El estado seleccionado no es válido.';
        }

        return $messages;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        $attributes = [
            'course_id' => 'curso',
            'title' => 'título',
            'description' => 'descripción',
            'type' => 'tipo de tarea',
            'start_date' => 'fecha de inicio',
            'due_date' => 'fecha de vencimiento',
            'priority' => 'prioridad',
        ];

        // Agregar atributo status si se está validando
        if ($this->has('status')) {
            $attributes['status'] = 'estado';
        }

        return $attributes;
    }
} 