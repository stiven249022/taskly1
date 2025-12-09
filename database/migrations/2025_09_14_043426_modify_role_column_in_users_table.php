<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero, cambiar la columna a string temporalmente
        DB::statement('ALTER TABLE users MODIFY COLUMN role VARCHAR(20)');
        
        // Luego, cambiar a enum con los tres valores
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'teacher', 'admin') DEFAULT 'student'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a enum original
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'teacher') DEFAULT 'student'");
    }
};