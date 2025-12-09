<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $courses = Course::all();
        $tags = Tag::all();

        if (!$user || $courses->isEmpty() || $tags->isEmpty()) {
            return;
        }

        $projects = [
            [
                'name' => 'Sistema de Gestión Escolar',
                'description' => 'Desarrollo de una plataforma web para la gestión de estudiantes y cursos',
                'priority' => 'high',
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
                'status' => 'active',
                'progress' => 30,
                'course_id' => $courses[0]->id,
                'tags' => [$tags[0]->id, $tags[6]->id]
            ],
            [
                'name' => 'Base de Datos Distribuida',
                'description' => 'Implementación de una base de datos distribuida con alta disponibilidad',
                'priority' => 'medium',
                'start_date' => now(),
                'end_date' => now()->addMonths(1),
                'status' => 'active',
                'progress' => 50,
                'course_id' => $courses[1]->id,
                'tags' => [$tags[1]->id, $tags[6]->id]
            ],
            [
                'name' => 'Sistema de Recomendación',
                'description' => 'Desarrollo de un sistema de recomendación usando machine learning',
                'priority' => 'high',
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'status' => 'active',
                'progress' => 20,
                'course_id' => $courses[2]->id,
                'tags' => [$tags[0]->id, $tags[6]->id]
            ]
        ];

        foreach ($projects as $project) {
            $tags = $project['tags'];
            unset($project['tags']);

            $project = Project::create([
                'user_id' => $user->id,
                ...$project
            ]);

            $project->tags()->attach($tags);
        }
    }
} 