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
        // Agregar teacher_id a tasks
        if (!Schema::hasColumn('tasks', 'teacher_id')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->unsignedBigInteger('teacher_id')->nullable()->after('user_id');
                $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
            });
        }
        
        // Agregar teacher_id a projects
        if (!Schema::hasColumn('projects', 'teacher_id')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->unsignedBigInteger('teacher_id')->nullable()->after('user_id');
                $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
        
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};