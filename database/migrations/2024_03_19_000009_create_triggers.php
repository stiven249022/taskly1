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
        // Trigger para actualizar updated_at automáticamente
        DB::unprepared('
            CREATE TRIGGER update_tasks_updated_at 
            BEFORE UPDATE ON tasks 
            FOR EACH ROW 
            SET NEW.updated_at = NOW();
        ');

        // Trigger para actualizar updated_at automáticamente en courses
        DB::unprepared('
            CREATE TRIGGER update_courses_updated_at 
            BEFORE UPDATE ON courses 
            FOR EACH ROW 
            SET NEW.updated_at = NOW();
        ');

        // Trigger para actualizar updated_at automáticamente en projects
        DB::unprepared('
            CREATE TRIGGER update_projects_updated_at 
            BEFORE UPDATE ON projects 
            FOR EACH ROW 
            SET NEW.updated_at = NOW();
        ');

        // Trigger para actualizar updated_at automáticamente en reminders
        DB::unprepared('
            CREATE TRIGGER update_reminders_updated_at 
            BEFORE UPDATE ON reminders 
            FOR EACH ROW 
            SET NEW.updated_at = NOW();
        ');

        // Trigger para validar que las fechas de las tareas sean lógicas
        DB::unprepared('
            CREATE TRIGGER validate_task_dates 
            BEFORE INSERT ON tasks 
            FOR EACH ROW 
            BEGIN
                IF NEW.start_date > NEW.due_date THEN
                    SIGNAL SQLSTATE "45000" 
                    SET MESSAGE_TEXT = "La fecha de inicio no puede ser posterior a la fecha de vencimiento";
                END IF;
            END;
        ');

        // Trigger para validar que las fechas de las tareas sean lógicas en actualizaciones
        DB::unprepared('
            CREATE TRIGGER validate_task_dates_update 
            BEFORE UPDATE ON tasks 
            FOR EACH ROW 
            BEGIN
                IF NEW.start_date > NEW.due_date THEN
                    SIGNAL SQLSTATE "45000" 
                    SET MESSAGE_TEXT = "La fecha de inicio no puede ser posterior a la fecha de vencimiento";
                END IF;
            END;
        ');

        // Trigger para registrar cambios en las tareas
        DB::unprepared('
            CREATE TRIGGER log_task_changes 
            AFTER UPDATE ON tasks 
            FOR EACH ROW 
            BEGIN
                IF OLD.status != NEW.status THEN
                    INSERT INTO task_logs (task_id, action, old_value, new_value, created_at) 
                    VALUES (NEW.id, "status_change", OLD.status, NEW.status, NOW());
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_tasks_updated_at');
        DB::unprepared('DROP TRIGGER IF EXISTS update_courses_updated_at');
        DB::unprepared('DROP TRIGGER IF EXISTS update_projects_updated_at');
        DB::unprepared('DROP TRIGGER IF EXISTS update_reminders_updated_at');
        DB::unprepared('DROP TRIGGER IF EXISTS validate_task_dates');
        DB::unprepared('DROP TRIGGER IF EXISTS validate_task_dates_update');
        DB::unprepared('DROP TRIGGER IF EXISTS log_task_changes');
    }
}; 