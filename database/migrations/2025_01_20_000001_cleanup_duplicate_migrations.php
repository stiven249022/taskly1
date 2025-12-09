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
        // Verificar si existen migraciones duplicadas y limpiarlas
        // Esta migración se ejecuta después de la limpieza principal
        
        // Optimizar índices en tablas principales
        DB::statement('ALTER TABLE users ADD INDEX idx_users_email (email)');
        DB::statement('ALTER TABLE users ADD INDEX idx_users_role (role)');
        DB::statement('ALTER TABLE users ADD INDEX idx_users_status (status)');
        
        DB::statement('ALTER TABLE tasks ADD INDEX idx_tasks_user_id (user_id)');
        DB::statement('ALTER TABLE tasks ADD INDEX idx_tasks_course_id (course_id)');
        DB::statement('ALTER TABLE tasks ADD INDEX idx_tasks_status (status)');
        DB::statement('ALTER TABLE tasks ADD INDEX idx_tasks_due_date (due_date)');
        
        DB::statement('ALTER TABLE projects ADD INDEX idx_projects_user_id (user_id)');
        DB::statement('ALTER TABLE projects ADD INDEX idx_projects_course_id (course_id)');
        DB::statement('ALTER TABLE projects ADD INDEX idx_projects_status (status)');
        DB::statement('ALTER TABLE projects ADD INDEX idx_projects_end_date (end_date)');
        
        DB::statement('ALTER TABLE courses ADD INDEX idx_courses_user_id (user_id)');
        DB::statement('ALTER TABLE courses ADD INDEX idx_courses_code (code)');
        
        DB::statement('ALTER TABLE reminders ADD INDEX idx_reminders_user_id (user_id)');
        DB::statement('ALTER TABLE reminders ADD INDEX idx_reminders_course_id (course_id)');
        DB::statement('ALTER TABLE reminders ADD INDEX idx_reminders_due_date (due_date)');
        
        DB::statement('ALTER TABLE tags ADD INDEX idx_tags_user_id (user_id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar índices agregados
        DB::statement('ALTER TABLE users DROP INDEX idx_users_email');
        DB::statement('ALTER TABLE users DROP INDEX idx_users_role');
        DB::statement('ALTER TABLE users DROP INDEX idx_users_status');
        
        DB::statement('ALTER TABLE tasks DROP INDEX idx_tasks_user_id');
        DB::statement('ALTER TABLE tasks DROP INDEX idx_tasks_course_id');
        DB::statement('ALTER TABLE tasks DROP INDEX idx_tasks_status');
        DB::statement('ALTER TABLE tasks DROP INDEX idx_tasks_due_date');
        
        DB::statement('ALTER TABLE projects DROP INDEX idx_projects_user_id');
        DB::statement('ALTER TABLE projects DROP INDEX idx_projects_course_id');
        DB::statement('ALTER TABLE projects DROP INDEX idx_projects_status');
        DB::statement('ALTER TABLE projects DROP INDEX idx_projects_end_date');
        
        DB::statement('ALTER TABLE courses DROP INDEX idx_courses_user_id');
        DB::statement('ALTER TABLE courses DROP INDEX idx_courses_code');
        
        DB::statement('ALTER TABLE reminders DROP INDEX idx_reminders_user_id');
        DB::statement('ALTER TABLE reminders DROP INDEX idx_reminders_course_id');
        DB::statement('ALTER TABLE reminders DROP INDEX idx_reminders_due_date');
        
        DB::statement('ALTER TABLE tags DROP INDEX idx_tags_user_id');
    }
};
