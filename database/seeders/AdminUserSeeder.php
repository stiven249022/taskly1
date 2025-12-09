<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador por defecto
        User::firstOrCreate(
            ['email' => 'admin@taskly.com'],
            [
                'name' => 'Administrador',
                'last_name' => 'Sistema',
                'email' => 'admin@taskly.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario profesor de ejemplo
        User::firstOrCreate(
            ['email' => 'profesor@taskly.com'],
            [
                'name' => 'Profesor',
                'last_name' => 'Ejemplo',
                'email' => 'profesor@taskly.com',
                'password' => Hash::make('profesor123'),
                'role' => 'teacher',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario estudiante de ejemplo
        User::firstOrCreate(
            ['email' => 'estudiante@taskly.com'],
            [
                'name' => 'Estudiante',
                'last_name' => 'Ejemplo',
                'email' => 'estudiante@taskly.com',
                'password' => Hash::make('estudiante123'),
                'role' => 'student',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}
