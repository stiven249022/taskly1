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
            $table->boolean('push_notifications')->default(true);
            $table->boolean('task_reminders')->default(true);
            $table->boolean('project_deadlines')->default(true);
            $table->boolean('exam_reminders')->default(true);
            $table->integer('reminder_frequency')->default(30); // minutos
            $table->string('language')->default('es');
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'push_notifications',
                'task_reminders',
                'project_deadlines',
                'exam_reminders',
                'reminder_frequency',
                'language',
                'bio',
                'phone',
                'location'
            ]);
        });
    }
};
