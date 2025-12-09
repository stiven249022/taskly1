<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TeacherStudentAssignment;
use App\Models\Course;

class TeacherStudentAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el profesor
        $teacher = User::where('email', 'profesor@taskly.com')->first();
        
        if (!$teacher) {
            $this->command->warn('Profesor no encontrado. Creando asignaciones de ejemplo...');
            return;
        }

        // Obtener todos los estudiantes
        $students = User::where('role', 'student')->get();
        
        if ($students->isEmpty()) {
            $this->command->warn('No hay estudiantes para asignar.');
            return;
        }

        // Obtener un curso existente o usar null
        $course = Course::first();

        // Asignar estudiantes al profesor
        foreach ($students as $student) {
            TeacherStudentAssignment::firstOrCreate([
                'teacher_id' => $teacher->id,
                'student_id' => $student->id,
                'course_id' => $course ? $course->id : null,
                'class_name' => 'Clase A - Matemáticas',
                'status' => 'active'
            ]);
        }

        $this->command->info('Asignaciones de estudiantes a profesor creadas exitosamente.');
    }
}