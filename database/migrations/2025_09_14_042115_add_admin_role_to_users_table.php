<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Modificar la columna role para incluir 'admin'
            $table->enum('role', ['student', 'teacher', 'admin'])->default('student')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revertir la columna role a su estado original
            $table->enum('role', ['student', 'teacher'])->default('student')->change();
        });
    }
};
