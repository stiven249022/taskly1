<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Reminder;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReminderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $courses = Course::all();
        $tags = Tag::all();

        if (!$user || $courses->isEmpty() || $tags->isEmpty()) {
            return;
        }

        $reminders = [
            [
                'title' => 'Entrega de Proyecto Final',
                'description' => 'Fecha límite para la entrega del proyecto final de Programación Web',
                'due_date' => now()->addDays(14),
                'status' => 'pending',
                'course_id' => $courses[0]->id,
                'tags' => [$tags[0]->id, $tags[4]->id]
            ],
            [
                'title' => 'Examen de Base de Datos',
                'description' => 'Examen parcial sobre diseño de bases de datos',
                'due_date' => now()->addDays(7),
                'status' => 'pending',
                'course_id' => $courses[1]->id,
                'tags' => [$tags[0]->id, $tags[4]->id]
            ],
            [
                'title' => 'Presentación de IA',
                'description' => 'Presentación sobre algoritmos de machine learning',
                'due_date' => now()->addDays(10),
                'status' => 'pending',
                'course_id' => $courses[2]->id,
                'tags' => [$tags[1]->id, $tags[4]->id]
            ]
        ];

        foreach ($reminders as $reminder) {
            $tags = $reminder['tags'];
            unset($reminder['tags']);

            $reminder = Reminder::create([
                'user_id' => $user->id,
                ...$reminder
            ]);

            $reminder->tags()->attach($tags);
        }
    }
} 