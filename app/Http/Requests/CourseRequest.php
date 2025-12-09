<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'code' => 'required|string|max:50|min:2',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'semester' => 'required|string|max:50',
            'professor' => 'required|string|max:255|min:2',
            'schedule' => 'required|string|max:255|min:5',
            'credits' => 'required|integer|min:1|max:10'
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
            'name.required' => 'El nombre de la materia es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'code.required' => 'El código de la materia es obligatorio.',
            'code.min' => 'El código debe tener al menos 2 caracteres.',
            'code.max' => 'El código no puede tener más de 50 caracteres.',
            'description.max' => 'La descripción no puede tener más de 1000 caracteres.',
            'color.required' => 'Debe seleccionar un color para la materia.',
            'color.regex' => 'El color debe ser un código hexadecimal válido.',
            'semester.required' => 'Debe seleccionar un semestre.',
            'semester.max' => 'El semestre no puede tener más de 50 caracteres.',
            'professor.required' => 'El nombre del profesor es obligatorio.',
            'professor.min' => 'El nombre del profesor debe tener al menos 2 caracteres.',
            'professor.max' => 'El nombre del profesor no puede tener más de 255 caracteres.',
            'schedule.required' => 'El horario es obligatorio.',
            'schedule.min' => 'El horario debe tener al menos 5 caracteres.',
            'schedule.max' => 'El horario no puede tener más de 255 caracteres.',
            'credits.required' => 'Debe seleccionar el número de créditos.',
            'credits.integer' => 'Los créditos deben ser un número entero.',
            'credits.min' => 'Los créditos deben ser al menos 1.',
            'credits.max' => 'Los créditos no pueden ser más de 10.',
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
            'name' => 'nombre de la materia',
            'code' => 'código de la materia',
            'description' => 'descripción',
            'color' => 'color',
            'semester' => 'semestre',
            'professor' => 'profesor',
            'schedule' => 'horario',
            'credits' => 'créditos',
        ];
    }
} 