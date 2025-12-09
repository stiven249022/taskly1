<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $tags = [
            [
                'name' => 'Urgente',
                'color' => '#FF0000'
            ],
            [
                'name' => 'Importante',
                'color' => '#FFA500'
            ],
            [
                'name' => 'Normal',
                'color' => '#00FF00'
            ],
            [
                'name' => 'Baja Prioridad',
                'color' => '#0000FF'
            ],
            [
                'name' => 'Examen',
                'color' => '#800080'
            ],
            [
                'name' => 'Tarea',
                'color' => '#008080'
            ],
            [
                'name' => 'Proyecto',
                'color' => '#FF00FF'
            ]
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'user_id' => $user->id,
                ...$tag
            ]);
        }
    }
} 