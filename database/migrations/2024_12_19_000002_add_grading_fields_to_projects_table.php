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
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('grade', 5, 2)->nullable()->after('status');
            $table->text('feedback')->nullable()->after('grade');
            $table->timestamp('graded_at')->nullable()->after('feedback');
            $table->foreignId('graded_by')->nullable()->constrained('users')->after('graded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['graded_by']);
            $table->dropColumn(['grade', 'feedback', 'graded_at', 'graded_by']);
        });
    }
};
