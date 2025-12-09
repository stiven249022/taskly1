<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('support_file_path')->nullable();
            $table->string('support_file_name')->nullable();
            $table->string('support_file_type')->nullable();
            $table->unsignedBigInteger('support_file_size')->nullable();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('support_file_path')->nullable();
            $table->string('support_file_name')->nullable();
            $table->string('support_file_type')->nullable();
            $table->unsignedBigInteger('support_file_size')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['support_file_path','support_file_name','support_file_type','support_file_size']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['support_file_path','support_file_name','support_file_type','support_file_size']);
        });
    }
};


