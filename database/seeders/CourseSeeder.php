<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $courses = [
            // Materias de Programación/Informática
            [
                'name' => 'Programación I',
                'code' => 'PROG101',
                'description' => 'Fundamentos de programación con Python',
                'color' => '#3B82F6',
                'semester' => 'Semestre 1',
                'professor' => 'Dr. María González',
                'schedule' => 'Lunes y Miércoles 08:00-10:00',
                'credits' => 4
            ],
            [
                'name' => 'Estructuras de Datos',
                'code' => 'PROG201',
                'description' => 'Implementación y uso de estructuras de datos',
                'color' => '#10B981',
                'semester' => 'Semestre 2',
                'professor' => 'Ing. Carlos Rodríguez',
                'schedule' => 'Martes y Jueves 10:00-12:00',
                'credits' => 4
            ],
            [
                'name' => 'Bases de Datos',
                'code' => 'DB301',
                'description' => 'Diseño y gestión de bases de datos relacionales',
                'color' => '#F59E0B',
                'semester' => 'Semestre 3',
                'professor' => 'MSc. Ana Martínez',
                'schedule' => 'Lunes y Viernes 14:00-16:00',
                'credits' => 3
            ],
            [
                'name' => 'Desarrollo Web',
                'code' => 'WEB401',
                'description' => 'Desarrollo de aplicaciones web modernas',
                'color' => '#EF4444',
                'semester' => 'Semestre 4',
                'professor' => 'Dr. Luis Pérez',
                'schedule' => 'Miércoles y Viernes 16:00-18:00',
                'credits' => 4
            ],
            [
                'name' => 'Inteligencia Artificial',
                'code' => 'AI501',
                'description' => 'Fundamentos de machine learning y IA',
                'color' => '#8B5CF6',
                'semester' => 'Semestre 5',
                'professor' => 'Dr. Elena Silva',
                'schedule' => 'Martes y Jueves 14:00-16:00',
                'credits' => 4
            ],
            
            // Materias de Matemáticas
            [
                'name' => 'Cálculo I',
                'code' => 'CALC101',
                'description' => 'Cálculo diferencial e integral',
                'color' => '#EC4899',
                'semester' => 'Semestre 1',
                'professor' => 'Dr. Roberto Díaz',
                'schedule' => 'Lunes y Miércoles 10:00-12:00',
                'credits' => 4
            ],
            [
                'name' => 'Álgebra Lineal',
                'code' => 'ALG201',
                'description' => 'Vectores, matrices y transformaciones lineales',
                'color' => '#06B6D4',
                'semester' => 'Semestre 2',
                'professor' => 'MSc. Patricia López',
                'schedule' => 'Martes y Jueves 08:00-10:00',
                'credits' => 3
            ],
            
            // Materias de Física
            [
                'name' => 'Física I',
                'code' => 'FIS101',
                'description' => 'Mecánica clásica y termodinámica',
                'color' => '#84CC16',
                'semester' => 'Semestre 1',
                'professor' => 'Dr. Fernando Morales',
                'schedule' => 'Lunes y Viernes 12:00-14:00',
                'credits' => 4
            ],
            [
                'name' => 'Física II',
                'code' => 'FIS201',
                'description' => 'Electricidad, magnetismo y ondas',
                'color' => '#F97316',
                'semester' => 'Semestre 2',
                'professor' => 'MSc. Gabriela Torres',
                'schedule' => 'Miércoles y Viernes 10:00-12:00',
                'credits' => 4
            ],
            
            // Materias de Humanidades
            [
                'name' => 'Comunicación Escrita',
                'code' => 'COM101',
                'description' => 'Técnicas de escritura académica y profesional',
                'color' => '#6366F1',
                'semester' => 'Semestre 1',
                'professor' => 'Lic. Carmen Ruiz',
                'schedule' => 'Martes y Jueves 16:00-18:00',
                'credits' => 2
            ],
            [
                'name' => 'Historia de la Tecnología',
                'code' => 'HIST301',
                'description' => 'Evolución tecnológica y su impacto social',
                'color' => '#14B8A6',
                'semester' => 'Semestre 3',
                'professor' => 'Dr. Jorge Mendoza',
                'schedule' => 'Lunes y Miércoles 16:00-18:00',
                'credits' => 3
            ],
            
            // Materias de Gestión
            [
                'name' => 'Gestión de Proyectos',
                'code' => 'GEST401',
                'description' => 'Metodologías y herramientas de gestión',
                'color' => '#F43F5E',
                'semester' => 'Semestre 4',
                'professor' => 'MSc. Ricardo Vega',
                'schedule' => 'Martes y Viernes 14:00-16:00',
                'credits' => 3
            ],
            [
                'name' => 'Emprendimiento',
                'code' => 'EMP501',
                'description' => 'Creación y desarrollo de empresas tecnológicas',
                'color' => '#A855F7',
                'semester' => 'Semestre 5',
                'professor' => 'Lic. Sofía Herrera',
                'schedule' => 'Jueves 18:00-20:00',
                'credits' => 2
            ]
        ];

        foreach ($courses as $course) {
            Course::create([
                'user_id' => $user->id,
                ...$course
            ]);
        }
    }
} 