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
        Schema::table('schedule_sessions', function (Blueprint $table) {
            // First drop the foreign key constraint
            $table->dropForeign(['course_id']);

            // Then drop the column
            $table->dropColumn('course_id');
        });

        // Add the new column and foreign key in a separate operation
        Schema::table('schedule_sessions', function (Blueprint $table) {
            $table->uuid('course_user_id')->nullable();
            $table->foreign('course_user_id')->references('id')->on('course_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_sessions', function (Blueprint $table) {
            $table->dropForeign(['course_user_id']);
            $table->dropColumn('course_user_id');
        });

        Schema::table('schedule_sessions', function (Blueprint $table) {
            $table->uuid('course_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses');
        });
    }
};
