<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario de ejemplo
        User::updateOrCreate(
            ['email' => 'usuario@taskly.com'],
            [
                'name' => 'Usuario Ejemplo',
                'password' => Hash::make('usuario123'),
                'role' => 'student',
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );

        // Crear algunos usuarios de ejemplo adicionales
        User::updateOrCreate(
            ['email' => 'maria@taskly.com'],
            [
                'name' => 'María García',
                'password' => Hash::make('maria123'),
                'role' => 'student',
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'juan@taskly.com'],
            [
                'name' => 'Juan Pérez',
                'password' => Hash::make('juan123'),
                'role' => 'student',
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );
    }
} 