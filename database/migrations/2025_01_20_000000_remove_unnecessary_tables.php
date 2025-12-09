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
        // Eliminar tabla task_logs que no se usa
        Schema::dropIfExists('task_logs');
        
        // Eliminar triggers innecesarios
        DB::unprepared('DROP TRIGGER IF EXISTS update_tasks_updated_at');
        DB::unprepared('DROP TRIGGER IF EXISTS update_courses_updated_at');
        DB::unprepared('DROP TRIGGER IF EXISTS update_projects_updated_at');
        DB::unprepared('DROP TRIGGER IF EXISTS update_reminders_updated_at');
        DB::unprepared('DROP TRIGGER IF EXISTS validate_task_dates');
        DB::unprepared('DROP TRIGGER IF EXISTS validate_task_dates_update');
        DB::unprepared('DROP TRIGGER IF EXISTS log_task_changes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recrear tabla task_logs
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->string('action');
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->timestamps();
        });

        // Recrear triggers (aunque no son necesarios)
        DB::unprepared('
            CREATE TRIGGER update_tasks_updated_at 
            BEFORE UPDATE ON tasks 
            FOR EACH ROW 
            SET NEW.updated_at = NOW();
        ');

        DB::unprepared('
            CREATE TRIGGER update_courses_updated_at 
            BEFORE UPDATE ON courses 
            FOR EACH ROW 
            SET NEW.updated_at = NOW();
        ');

        DB::unprepared('
            CREATE TRIGGER update_projects_updated_at 
            BEFORE UPDATE ON projects 
            FOR EACH ROW 
            SET NEW.updated_at = NOW();
        ');

        DB::unprepared('
            CREATE TRIGGER update_reminders_updated_at 
            BEFORE UPDATE ON reminders 
            FOR EACH ROW 
            SET NEW.updated_at = NOW();
        ');

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
};
