<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table('schedule_sessions', function (Blueprint $table) {
            $table->softDeletes();

            // Indexes
            $table->index('session_type_id');
            $table->index('course_id');
            $table->index('timetable_id');
            $table->index('room_id');
            $table->index('day');
            $table->index('status');

            // Unique constraint for overlapping sessions
            $table->unique(['room_id', 'day', 'start_time', 'end_time'], 'unique_room_schedule');
        });

        DB::statement('ALTER TABLE schedule_sessions ADD CONSTRAINT check_time_range CHECK (start_time < end_time)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_sessions');
    }
};
